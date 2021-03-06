// Generated by CoffeeScript 1.9.0
(function() {
  var Audio, ButtonListener, Cover, SwitchController, Video;

  this.localhost = "140.119.134.100";

  this.localhost = "192.168.1.100";

  SwitchController = (function() {
    var statusController, _judgePage;

    statusController = void 0;

    function SwitchController(_statusController) {
      statusController = _statusController;
    }

    SwitchController.prototype.closeNotification = function() {
      return $('#notiContainer').fadeOut();
    };

    SwitchController.prototype.openNotification = function() {
      return $('#notiContainer').fadeIn();
    };

    _judgePage = function(href) {
      if (href.indexOf("page=infoA") !== -1) {
        return 'A';
      }
      if (href.indexOf("page=infoB") !== -1) {
        return 'B';
      }
      if (href.indexOf("page=infoC") !== -1) {
        return 'C';
      }
      if (href.indexOf("page=build") !== -1) {
        return 'build';
      }
      console.warn("switchController.judgePage got no answer!");
      return 'n';
    };

    SwitchController.prototype.prepareStatus = function(behavior, object_type) {
      var obj;
      return obj = {
        behavior: behavior,
        object_type: object_type
      };
    };

    SwitchController.prototype.ajaxPage = function(href, status, callback) {
      var page;
      if (href.indexOf('-1') > -1) {
        return false;
      }
      console.log(status);
      loadingCover.show();
      audio.playClick();
      page = _judgePage(href);
      return $.get("http://" + localhost + "/co-construction/php/controller.php?" + href, function(data, textStatus) {
        var $curPage, $newPage;
        if (textStatus !== "success") {
          return alert("get page failed");
        } else if (textStatus === "success") {
          $curPage = $('.Page.active');
          $newPage = $("<div class='Page' style='opacity:0.0'>" + data + "</div>");
          $newPage = $newPage.appendTo('#pageContainer');
          if (status !== false) {
            setTimeout(function() {
              status.object = localStorage['status'];
              statusController.addStatus(status);
              if (callback != null) {
                return callback();
              }
            }, 200);
          }
          return setTimeout(function() {
            return $curPage.animate({
              opacity: 0.0
            }, 600, function() {
              $curPage.remove();
              loadingCover.hide();
              return $newPage.delay(100).animate({
                opacity: 1
              }, 600).promise().done(function() {
                $newPage.addClass("active");
                if (typeof effectAnimate === "function") {
                  return effectAnimate();
                }
              });
            });
          }, 800);
        }
      });
    };

    return SwitchController;

  })();

  ButtonListener = (function() {
    var switchController, _main;

    switchController = void 0;

    _main = void 0;

    function ButtonListener(_switchController) {
      _main = this;
      switchController = _switchController;
      $(document).on("click", ".toLookUp.building", function(e) {
        var status;
        e.preventDefault();
        status = switchController.prepareStatus("LOOK_UP", "B");
        switchController.ajaxPage($(this).data('ajax'), status);
        return false;
      });
      $(document).on("click", ".toLookUp.type", function(e) {
        var status;
        e.preventDefault();
        status = switchController.prepareStatus("LOOK_UP", "T");
        switchController.ajaxPage($(this).data('ajax'), status);
        return false;
      });
      $(document).on("click", ".toLookUp.tag", function(e) {
        var status;
        e.preventDefault();
        status = switchController.prepareStatus("LOOK_UP", "G");
        switchController.ajaxPage($(this).data('ajax'), status);
        return false;
      });
    }

    return ButtonListener;

  })();

  Audio = (function() {
    var $audio;

    $audio = void 0;

    function Audio() {
      $audio = $("#clickAudio");
    }

    Audio.prototype.playClick = function() {
      return $audio[0].play();
    };

    return Audio;

  })();

  Cover = (function() {
    var $cover;

    $cover = void 0;

    function Cover() {
      $cover = $('#clickCover');
    }

    Cover.prototype.show = function() {
      return $cover.addClass('active');
    };

    Cover.prototype.hide = function() {
      return $cover.removeClass('active');
    };

    return Cover;

  })();

  Video = (function() {
    var $current, $videoAB, $videoBuild, $videoC, _change;

    $current = void 0;

    $videoAB = void 0;

    $videoC = void 0;

    $videoBuild = void 0;

    function Video() {
      $videoAB = $("#vAB");
      $videoC = $("#vC");
      $videoBuild = $("#vBuild");
    }

    _change = function($v) {
      if ($current != null) {
        $current.hide();
        $current[0].pause();
      }
      $current = $v;
      if ($current != null) {
        $current[0].play();
        return $current.show();
      }
    };

    Video.prototype.play = function(id) {
      switch (id) {
        case 'A':
        case 'B':
          return _change($videoAB);
        case 'C':
          return _change($videoC);
        case 'build':
          return _change($videoBuild);
        default:
          return _change($videoAB);
      }
    };

    return Video;

  })();

  this.Audio = Audio;

  this.SwitchController = SwitchController;

  this.ButtonListener = ButtonListener;

  this.radian = true;

  $(function() {
    var db, initStatus, pad;
    pad = new Pad();
    db = new DB();
    window.statusController = new Controller(pad, db);
    window.switchController = new SwitchController(statusController);
    window.audio = new Audio();
    window.loadingCover = new Cover();
    ButtonListener = new ButtonListener(switchController);
    statusController.track();
    initStatus = switchController.prepareStatus("LOOK_UP", "B");
    return switchController.ajaxPage("page=infoA&bid=1", initStatus);
  });

}).call(this);
