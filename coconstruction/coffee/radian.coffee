# width : 2504px
# height : 720px

lib =
  getParameterByName : (name) ->
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')
    regex = new RegExp('[\\?&]' + name + '=([^&#]*)')
    results = regex.exec(location.search)
    if results == null then '' else decodeURIComponent(results[1].replace(/\+/g, ' '))


DEVICE_ID = 'F'
localStorage['deviceid'] = DEVICE_ID

LAZY_SECONDS = 10
RATE = 1000
NOTI_TIME = 5000


class Controller
  db = undefined
  notificationController = undefined

  $radian = $("#radian")

  _includeCss = ()->
    head  = document.getElementsByTagName('head')[0];
    link  = document.createElement('link');
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = 'css/project.css';
    link.media = 'all';
    head.appendChild(link);


  init = () ->
    _includeCss();
    $radian.html
    """
      <div id="audios">
        <audio id="liveInAudio">
          <source src="audio/liveIn.mp3" type="audio/mpeg">Your browser does not support the audio tag.
        </audio>
        <audio id="buildUpAudio">
          <source src="audio/buildUp.mp3" type="audio/mpeg">Your browser does not support the audio tag.
        </audio>
      </div>

      <div id="notiContainer">
        <div id="liveInModal" class="notiModal ">
          <div class="inner"></div>
        </div>
        <div id="buildUpModal" class="notiModal">
          <div class="inner"></div>
        </div>
      </div>
    """


  constructor : ( _NotificationController , _db ) ->
    db = _db
    notificationController = _NotificationController
    @building = undefined
    main = this
    init();

  _track = () ->
    db.track(_trackCallback).fail ()->
      console.log 'trace_fail , continuing'
      setTimeout _track , RATE

  _isLazy = () ->
    d = new Date()
    return ((d.getTime() - _lastMoveTime)/1000) > LAZY_SECONDS

  _trackCallback = (data) ->
    console.log("tracked." , data ) ;
    for status in data
      status = new Status (status)
      if (status.isCHECK_IN() or status.isBUILD_UP()) and not status.isRead()
        noti = new Notification(status)
        db.markReadStatus(status)
        console.log "coming status : " , status
      else


    setTimeout _track , RATE

  track : () ->
    console.log "start tracking"
    setTimeout _track , RATE



  busy : () -> #
    d = new Date()
    _lastMoveTime = d.getTime()

  wait : () -> #等待頁面 ajax 完成

  isLazy : () ->
    _isLazy()


  addStatus : (data,callback) ->
    db.addStatus(data,callback)



class Status
  thisStatus = undefined
  comingData = undefined
  constructor : (data)->
    thisStatus = @
    comingData = data
    for attrname of data
      @[attrname] = data[attrname]

    if (not @.isBUILDING())
      try
        test =  JSON.parse(@object)
      catch e
        console.log(e,data)
        test =
          bid: "1"
          bname: "映山華廈"
      @object_real = test

    @["event"] = @getEvent()
    console.log @["event"]

  isRead : () ->
    if @done
      @done.indexOf(DEVICE_ID) >= 0
    else
      false
  isSelf : () ->
    @device is DEVICE_ID
  isPad : () ->
    @device is "A" or @device is "B"  or @device is "C"
  isDesk : () ->
    @device is "D"
  isProject : ()->
    @device is "E"
  isNull : ()->
    @device isnt "A" and @device isnt "B"  and @device isnt "C" and @device isnt "D" and @device isnt "E" and @device isnt "F"
  isCHECK_IN : () ->
    @behavior is "LIVE_IN"
  isLOOK_UP : () ->
    @behavior is "LOOK_UP"

  isBUILDING : () ->       # 建造中
    @behavior is "BUILD_UP"
  isBUILD_UP : () ->
    @behavior is "BUILD_UP_after"
  isBUILD_UP_RETURN : ()->
    @behavior is "BUILD_UP_return"

  liveTime : () ->
    o = new Date(@time)
    d = new Date()
    Math.ceil((d.getTime() - o.getTime()) /1000)

  getEvent : () ->
    if @isNull()
      return "DeviceNull"
    if @isSelf()
      return "self"
    if @isPad()

      if @isBUILDING()
        return "otherPad.building"
      if @isBUILD_UP()
        return "otherPad.buildUp"
      if @isCHECK_IN()
        return "otherPad.liveIn"

      if @isLOOK_UP()
        return "otherPad.lookUp.building" if @object_type is "B"
        return "otherPad.lookUp.type" if @object_type is "T"
        return "otherPad.lookUp.tag" if @object_type is "G"

    else if @isDesk()
      return "desk.lookUp" if @isLOOK_UP()
      return "desk.building" if @isBUILD_UP_RETURN()

    else
      console.log(comingData , "unsolve") ;
      return "unsolve"

class DB
  constructor : () ->

  pull : (url , callback) ->
    # 抓下後 upadate "done" with "deviceID"
    $.get(url,callback).fail((j,s,e) ->
      console.log(j.responseText,s,e)
    )

  push : (url,data,callback) ->
    # 這裡要推資料上去，會return true (accept) 或 false(refuse)
    $.post(url,data).done(callback)

  clearStatus : (match,callback) ->
    # console.clear()
    if !callback
      callback = () ->
    console.log callback
    if !match
      @push "data/clearStatus.php" ,
        device : DEVICE_ID
      , (data,textStatus) ->
        callback()
        console.log(data , textStatus ,"Status of #{DEVICE_ID} is deleted.")
    else if match.statusid
      @push "data/clearStatus.php" ,
        statusid : match.statusid , (data,textStatus) ->
        callback()
        console.log(textStatus ,"Status #{status.statusid} is deleted.")


  markReadStatus : (status) ->
    data =
      done : DEVICE_ID
      statusid : status.statusid
    @push "data/updateStatus.php" , data , () ->
      console.log("Status #{status.statusid} is mark read.")

  addStatus : (data,callback) ->
    lco = JSON.parse(data.object) ;
    lc = 0
    switch data.object_type
      when "T" then lc = lco.cid
      when "B" then lc = lco.bid
      when "G" then lc = lco.gid
      else console.log("Rn.expect object_type,now set oid to 1" )
    console.log lc , lco


    _addStatus = ()->
      @push "data/addStatus.php" ,
        deviceid : DEVICE_ID
        behavior : data.behavior
        object_type : data.object_type
        object : data.object
        oid : lc
        time: new Date()
       , () ->
         if callback then callback()
         console.log("#{data.behavior}")

    @clearStatus().success _addStatus.bind this
  # 要新增建築資料
  buildUp : (object,callback) ->
    if object #現在回傳資料
      @addStatus
        behavior :"BUILD_UP"
      ,callback

  buildUpAfter : () ->
    @push "data/buildUp.php",object,callback
    @addStatus(
      behavior : "BUILD_UP_after"
      object_type : "B"
      object : object
    ,callback)


  buildUpPre : (callback) ->
    @addStatus
      behavior : "BUILD_UP_pre"
    ,callback

  checkIn : (object,callback) ->
    @push "data/checkIn.php",
      object : object
      object_type : "B"
      user : user_id
    ,callback # 這裡要去推資料進去資料庫
    @addStatus(
      behavior : "CHECK_IN"
      object_type : "B"
      object : object
    , callback)

  lookUp : (data, callback) ->
    #[] # 這時候要去拉資料回來
    @addStatus data , callback

  track : (callback) ->
    @pull "data/pullStatus.php" , callback
    #loop setTimeout track , 800  # 這個之後會用 ajax.done(_trackingDB) 取代


class Notification
  THIS = undefined
  getNotiStructure : () ->
    noti = THIS.getData(status)
    """
      <a class="noti animated fadeIn">
        <p class="#{noti[1]}">#{noti[0]}</p>
        <span class='close'></span>
      </a>
    """

  constructor : (status) ->
    THIS = @
    status.objString = @object2String (status)
    status.href = @getHref(status)

    #[通知內文, class , href ]
  getData : (status)->
    id = status.device
    url = status.href
    padStr = """<span class="#{id}">平板#{id}</span>"""

    switch status.event
      when "otherPad.buildUp"
      then ["#{padStr}建造了！" , "from#{id} toLookUp building" , url]

      when "otherPad.liveIn"
      then ["#{padStr}入住了#{status.objString}" , "from#{id} toLookUp building" , url]

      else
        console.log status
        ["無","hidden","url"]
  object2String : (status) ->
    span = (name) ->
      """<br>「<span class="highlight">#{name}</span>」"""

    console.log(status.object_real)
    switch status.object_type

      when "B" then "建築:#{span(status.object_real.bname)}"
      when "T" then "建築類型:#{span(status.object_real.tname)}"
      when "G" then "建築標籤:#{span(status.object_real.gname)}"
  getHref : (status) ->
    href = ""
    switch status.object_type

      when "B" then href = "page=infoA&bid=#{status.object_real.bid}"
      when "T" then href = "page=infoB&cid=#{status.object_real.cid}"
      when "G" then href = "page=infoC&gid=#{status.object_real.gid}"

    if href.length > 0
      return href


class Audio
  $liveIn = undefined ;
  $buildUp = undefined ;

  constructor : () ->
    $liveIn = $('#liveInAudio')
    $buildUp = $('#buildUpAudio')

  liveIn : ()->
    $liveIn[0].play() ;
  buildUp : ()->
    $buildUp[0].play() ;

class Timer
  timerId = undefined
  start = undefined
  remaining = undefined
  constructor : (callback, delay) ->
    remaining = delay
    @resume()

  @pause : ->
    window.clearTimeout timerId
    remaining -= new Date - start
    return

  @resume : ->
    start = new Date
    window.clearTimeout timerId
    timerId = window.setTimeout(callback, remaining)
    return

class NotificationController
  THIS = undefined
  notiArr = []
  $notifying = undefined
  $notiContainer = undefined

  prpareNotiContainer = (selector)->
    $notiContainer = $(selector) ;
    $(document).on 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend' ,"#{selector}.fadeOut" , ()->
      THIS.next()

    $(document).on 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend' ,"#{selector}.fadeIn",()->
      timer = new Timer (()->
        $(this).fadeOut()
      ),NOTI_TIME

    $(selector).fadeIn = () ->
      $notiContainer.toggleClass('fadeOut', false );
      $notiContainer.toggleClass('fadeIn', true );
    $(selector).fadeOut = () ->
      $notiContainer.toggleClass('fadeOut', true );
      $notiContainer.toggleClass('fadeIn', false );
    return $(selector)

  constructor : ()->
    THIS = @
    prpareNotiContainer("#notiContainer")

  comming : (noti)->
    if not $notifying?
      $notifying = $(noti.getNotiStructure()).appendTo($notiContainer)
      $(selector).fadeIn()
    else
      THIS.add(noti)

  next : ()->
    nextNoti = notiArr.shift()
    if (nextNoti?)
      THIS.comming(nextNoti)

  add : (noti) ->
    notiArr.push (noti)

this.Notification = Notification
this.DB = DB
this.Controller = Controller
this.Status = Status


notificationController = new NotificationController()
db = new DB()
main = new Controller(notificationController, db)
main.track();
