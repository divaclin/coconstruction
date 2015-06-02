// Generated by CoffeeScript 1.9.0
(function() {
  var Controller, CurrentTestData, DB, DEVICE_ID, LAZY_SECONDS, NOTI_TIME, Notification, Pad, RATE, Status, deviceid, lib, user_id;

  user_id = 15;

  CurrentTestData = JSON.stringify($("#phpData").val());

  lib = {
    getParameterByName: function(name) {
      var regex, results;
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      results = regex.exec(location.search);
      if (results === null) {
        return '';
      } else {
        return decodeURIComponent(results[1].replace(/\+/g, ' '));
      }
    }
  };

  deviceid = lib.getParameterByName("deviceid");

  localStorage['deviceid'] = deviceid;

  if (deviceid && deviceid.length === 1) {
    DEVICE_ID = deviceid;
  } else {
    DEVICE_ID = prompt("plz type deviceid");
  }

  $("title").text(deviceid);

  LAZY_SECONDS = 10;

  RATE = 1000;

  NOTI_TIME = 10;

  Controller = (function() {
    var db, pad, _isLazy, _lastMoveTime, _track, _trackCallback;

    db = void 0;

    pad = void 0;

    function Controller(_pad, _db, _at_notiController) {
      var main;
      this.notiController = _at_notiController;
      db = _db;
      pad = _pad;
      this.building = void 0;
      main = this;
    }

    _track = function() {
      return db.track(_trackCallback).fail(function() {
        console.log('trace_fail , continuing');
        return setTimeout(_track, RATE);
      });
    };

    _isLazy = function() {
      var d;
      d = new Date();
      return ((d.getTime() - _lastMoveTime) / 1000) > LAZY_SECONDS;
    };

    _trackCallback = function(data) {
      var noti, status, _i, _len;
      console.log("tracked.", data);
      for (_i = 0, _len = data.length; _i < _len; _i++) {
        status = data[_i];
        status = new Status(status);
        if (status.isSelf() && _isLazy()) {
          db.clearStatus();
        }
        if (status.isRead() || status.isSelf()) {

        } else {
          console.log("coming status : ", status);
          noti = new Notification(status);
          pad.noti(noti);
          db.markReadStatus(status);
        }
      }
      return setTimeout(_track, RATE);
    };

    Controller.prototype.track = function() {
      console.log("start tracking");
      return setTimeout(_track, RATE);
    };

    Controller.prototype.init = function() {
      var href, type;
      href = "http://" + localhost + "/co-construction/infoA?bid=1";
      main.wait();
      $.get(href, function(data, textStatus, jqXHR) {
        pad.$infoA.empty().append(data);
        data = pad.$infoA.find("phpData");
        main.lookUp("A", data);
        return pad.navTo(pad.current());
      });
      type = $(this).data("type");
      return main.lookUp("T", JSON.stringify(CurrentTestData.T.data));
    };


    /*
    ## the most imoortant part !!!
    
    judge : (status) ->
      console.log "status.isSelf()" , status.isSelf()
      if status.isRead() or status.isSelf()
        return "nothing"
    
       * 跟自己有關
      if building && status.isDesk() && status.isBUILD_UP()
        @buildUp(status)
        return string = "Build Up a building"
      else #if status.isDesk() && status.isLOOK_UP
        noti = new Notification (status)
        pad.noti(noti)
                                     ##
     */

    Controller.prototype.checkIn = function(object) {
      db.checkIn(object);
      return main.busy();
    };

    Controller.prototype.buildUp = function(status) {
      main.busy();
      if (status) {
        pad.buildUp(status);
        db.buildUp();
        return db.lookUp(status);
      } else {
        pad.buildUp();
        db.buildUp();
        return this.building = true;
      }
    };

    Controller.prototype.lookUp = function(object_type, object) {
      main.busy();
      return db.lookUp({
        behavior: "LOOK_UP",
        object_type: object_type,
        object: object
      }, function(data) {
        return console.log(data, "pushed");
      });
    };

    _lastMoveTime = void 0;

    Controller.prototype.busy = function() {
      var d;
      d = new Date();
      return _lastMoveTime = d.getTime();
    };

    Controller.prototype.wait = function() {};

    Controller.prototype.isLazy = function() {
      return _isLazy();
    };

    Controller.prototype.addStatus = function(data, callback) {
      return db.addStatus(data, callback);
    };

    return Controller;

  })();

  Status = (function() {
    var thisStatus;

    thisStatus = void 0;

    function Status(data) {
      var attrname, e, test;
      thisStatus = this;
      for (attrname in data) {
        this[attrname] = data[attrname];
      }
      try {
        test = JSON.parse(this.object);
      } catch (_error) {
        e = _error;
        console.log(e, data, this.object);
        test = {
          bid: "1",
          bname: "映山華廈"
        };
      }
      this.object_real = test;
      this["event"] = this.getEvent();
      console.log(this["event"]);
    }

    Status.prototype.isRead = function() {
      if (this.done) {
        return this.done.indexOf(DEVICE_ID) >= 0;
      } else {
        return false;
      }
    };

    Status.prototype.isSelf = function() {
      return this.device === DEVICE_ID;
    };

    Status.prototype.isPad = function() {
      return this.device === "A" || this.device === "B" || this.device === "C";
    };

    Status.prototype.isDesk = function() {
      return this.device === "D";
    };

    Status.prototype.isCHECK_IN = function() {
      return this.behavior === "CHECK_IN";
    };

    Status.prototype.isBUILD_UP = function() {
      return this.behavior === "BUILD_UP";
    };

    Status.prototype.isBUILD_PRE = function() {
      return this.behavior === "BUILD_UP_pre";
    };

    Status.prototype.isBUILD_AFTER = function() {
      return this.behavior === "BUILD_UP_after";
    };

    Status.prototype.isLOOK_UP = function() {
      return this.behavior === "LOOK_UP";
    };

    Status.prototype.isBUILD_UP_return = function() {
      return this.behavior === "BUILD_UP_return";
    };

    Status.prototype.liveTime = function() {
      var d, o;
      o = new Date(this.time);
      d = new Date();
      return Math.ceil((d.getTime() - o.getTime()) / 1000);
    };

    Status.prototype.getEvent = function() {
      if (this.isSelf()) {
        return "self";
      }
      if (this.isPad()) {
        if (this.isBUILD_UP()) {
          if (this.object_type) {
            return "otherPad.buildUp";
          } else {
            return "otherPad.building";
          }
        }
        if (this.isCHECK_IN()) {
          return "otherPad.checkIn";
        }
        if (this.isLOOK_UP()) {
          if (this.object_type === "B") {
            return "otherPad.lookUp.building";
          }
          if (this.object_type === "T") {
            return "otherPad.lookUp.type";
          }
          if (this.object_type === "G") {
            return "otherPad.lookUp.tag";
          }
        }
      } else if (this.isDesk()) {
        if (this.isLOOK_UP()) {
          return "desk.lookUp";
        }
        if (this.isBUILD_UP_return) {
          return "desk.building";
        }
      } else {
        console.log(thisStatus, "unsolve");
        return "unsolve";
      }
    };

    return Status;

  })();

  DB = (function() {
    function DB() {}

    DB.prototype.pull = function(url, callback) {
      return $.get(url, callback).fail(function(j, s, e) {
        return console.log(j.responseText, s, e);
      });
    };

    DB.prototype.push = function(url, data, callback) {
      return $.post(url, data).done(callback);
    };

    DB.prototype.clearStatus = function(match, callback) {
      if (!callback) {
        callback = function() {};
      }
      console.log(callback);
      if (!match) {
        return this.push("data/clearStatus.php", {
          device: DEVICE_ID
        }, function(data, textStatus) {
          callback();
          return console.log(data, textStatus, "Status of " + DEVICE_ID + " is deleted.");
        });
      } else if (match.statusid) {
        return this.push("data/clearStatus.php", {
          statusid: match.statusid
        }, function(data, textStatus) {}, callback(), console.log(textStatus, "Status " + status.statusid + " is deleted."));
      }
    };

    DB.prototype.markReadStatus = function(status) {
      var data;
      data = {
        done: DEVICE_ID,
        statusid: status.statusid
      };
      return this.push("data/updateStatus.php", data, function() {
        return console.log("Status " + status.statusid + " is mark read.");
      });
    };

    DB.prototype.addStatus = function(data, callback) {
      var lc, lco, _addStatus;
      lco = JSON.parse(data.object);
      lc = 0;
      switch (data.object_type) {
        case "T":
          lc = lco.cid;
          break;
        case "B":
          lc = lco.bid;
          break;
        case "G":
          lc = lco.gid;
          break;
        default:
          console.log("Rn.expect object_type,now set oid to 1");
      }
      console.log(lc, lco);
      _addStatus = function() {
        return this.push("data/addStatus.php", {
          deviceid: DEVICE_ID,
          behavior: data.behavior,
          object_type: data.object_type,
          object: data.object,
          oid: lc,
          time: new Date()
        }, function() {
          if (callback) {
            callback();
          }
          return console.log("" + data.behavior);
        });
      };
      return this.clearStatus().success(_addStatus.bind(this));
    };

    DB.prototype.buildUp = function(object, callback) {
      if (object) {
        return this.addStatus({
          behavior: "BUILD_UP"
        }, callback);
      }
    };

    DB.prototype.buildUpAfter = function() {
      this.push("data/buildUp.php", object, callback);
      return this.addStatus({
        behavior: "BUILD_UP_after",
        object_type: "B",
        object: object
      }, callback);
    };

    DB.prototype.buildUpPre = function(callback) {
      return this.addStatus({
        behavior: "BUILD_UP_pre"
      }, callback);
    };

    DB.prototype.checkIn = function(object, callback) {
      this.push("data/checkIn.php", {
        object: object,
        object_type: "B",
        user: user_id
      }, callback);
      return this.addStatus({
        behavior: "CHECK_IN",
        object_type: "B",
        object: object
      }, callback);
    };

    DB.prototype.lookUp = function(data, callback) {
      return this.addStatus(data, callback);
    };

    DB.prototype.track = function(callback) {
      return this.pull("data/pullStatus.php", callback);
    };

    return DB;

  })();

  Pad = (function() {
    var $body, $notiContainer;

    $body = $("body");

    $notiContainer = $("#notiContainer");

    function Pad(_at_id) {
      this.id = _at_id;
      this.$infoA = $("#INFO_A");
      this.$infoB = $("#INFO_B");
      this.$infoC = $("#INFO_C");
      this.$infoC = $("#BUILD");
    }

    Pad.prototype.noti = function(noti) {
      console.log("apd", noti);
      noti.prependTo("#notiContainer").fadeIn();
      return setTimeout(function() {
        var complete;
        return noti.fadeOut(complete = function() {
          return noti.remove();
        });
      }, NOTI_TIME * 3000);
    };

    Pad.prototype.navTo = function(padObject) {
      $(".active").not(padObject).fadeOut();
      return padObject.fadeIn(function() {
        return padObject.addClass("active");
      });
    };

    Pad.prototype.currentPage = function() {
      var a;
      a = $(".Page.active");
      console.log(a.length, a);
      return a;
    };

    Pad.prototype.checkIn = function() {};

    Pad.prototype.buildUp = function() {};

    return Pad;

  })();

  Notification = (function() {
    var _getNotiStructure;

    _getNotiStructure = function(noti) {
      return "<a class=\"noti " + noti[1] + "\" sytle=\"display:none;\" data-ajax=\"" + noti[2] + "\">\n  <p>" + noti[0] + "</p>\n</a>";
    };

    function Notification(status) {
      var data, jq;
      status.objString = this.object2String(status);
      status.href = this.getHref(status);
      data = this.getData(status);
      console.log(data);
      jq = $(_getNotiStructure(data));
      $.extend(this, jq);
    }

    Notification.prototype.getData = function(status) {
      var id, url;
      id = status.device;
      url = status.href;
      switch (status.event) {
        case "otherPad.building":
          return ["平板" + id + "正在建造" + status.objString + "中！", "from" + id + " noClick", url];
        case "otherPad.buildUp":
          return ["平板" + id + "建造了！", "from" + id + " toLookUp building", url];
        case "otherPad.checkIn":
          return ["平板" + id + "入住了一棟建築物！", "from" + id + " toLookUp building", url];
        case "otherPad.lookUp.building":
          return ["平板" + id + "正在查找" + status.objString + "！", "from" + id + " toLookUp building", url];
        case "otherPad.lookUp.type":
          return ["平板" + id + "正在查找" + status.objString + "！", "from" + id + " toLookUp type", url];
        case "otherPad.lookUp.tag":
          return ["平板" + id + "正在查找" + status.objString + "！", "from" + id + " toLookUp tag", url];
        case "desk.lookUp":
          return ["桌上有一筆查詢", "from" + id + " toLookUp building", url];
        case "desk.building":
          return ["桌上有一筆建造", "from" + id + " toLookUp building", url];
        case "unsolve":
          return ["有一筆未處理的狀態來自" + id, "from" + id + " ", url];
      }
    };

    Notification.prototype.object2String = function(status) {
      var span;
      span = function(name) {
        return "<span class=\"highlight\">" + name + "</span>";
      };
      console.log(status.object_real);
      switch (status.object_type) {
        case "B":
          return "建築:" + (span(status.object_real.bname));
        case "T":
          return "建築類型" + status.object_real.tname;
        case "G":
          return "建築標簽" + (span(status.object_real.gname));
      }
    };

    Notification.prototype.getHref = function(status) {
      var href;
      href = "";
      switch (status.object_type) {
        case "B":
          href = "page=infoA&bid=" + status.object_real.bid;
          break;
        case "T":
          href = "page=infoB&cid=" + status.object_real.cid;
          break;
        case "G":
          href = "page=infoC&gid=" + status.object_real.gid;
      }
      if (href.length > 0) {
        return href;
      }
    };

    return Notification;

  })();

  this.Pad = Pad;

  this.Notification = Notification;

  this.DB = DB;

  this.Controller = Controller;

  this.Status = Status;

}).call(this);
