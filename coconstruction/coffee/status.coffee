
user_id = 15
CurrentTestData = JSON.stringify $("#phpData").val()

lib =
  getParameterByName : (name) ->
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')
    regex = new RegExp('[\\?&]' + name + '=([^&#]*)')
    results = regex.exec(location.search)
    if results == null then '' else decodeURIComponent(results[1].replace(/\+/g, ' '))

deviceid = lib.getParameterByName("deviceid")

if deviceid and deviceid.length is 1
  DEVICE_ID = deviceid
else
  DEVICE_ID = prompt "plz type deviceid"

localStorage['deviceid'] = DEVICE_ID

$("title").text deviceid
LAZY_SECONDS = 10
RATE = 1000
NOTI_TIME = 20000


class Controller
  db = undefined
  pad = undefined

  constructor : (_pad , _db ,  @notiController) ->
    db = _db
    pad = _pad
    @building = undefined
    main = this
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
      # if status.isSelf() and _isLazy()
      #   db.clearStatus()
      if status.isRead() or status.isSelf() or stauts.isBUILD_UP_RETURN() or stauts.isNull()
      else
        if status.isBUILD_UP() or isCHECK_IN()
          if projectUpdate? then projectUpdate() else console.warrning 'projectUpdate not exist' 
        noti = new Notification(status)
        pad.noti(noti)

      console.log "coming status : " , status
      db.markReadStatus(status)

    setTimeout _track , RATE

  track : () ->
    console.log "start tracking"
    setTimeout _track , RATE

  init : () ->

    href = "http://#{localhost}/co-construction/infoA?bid=1"
    main.wait()
    $.get href , (data, textStatus, jqXHR) ->
      pad.$infoA.empty().append(data)
      data = pad.$infoA.find "phpData"
      main.lookUp "A" , data
      pad.navTo(pad.current())

    type = $(this).data("type")
    main.lookUp("T",JSON.stringify(CurrentTestData.T.data))

  ###
  ## the most imoortant part !!!

  judge : (status) ->
    console.log "status.isSelf()" , status.isSelf()
    if status.isRead() or status.isSelf()
      return "nothing"

    # 跟自己有關
    if building && status.isDesk() && status.isBUILD_UP()
      @buildUp(status)
      return string = "Build Up a building"
    else #if status.isDesk() && status.isLOOK_UP
      noti = new Notification (status)
      pad.noti(noti)
                                   ##
  ###################################


  ## 這屬於 本機要寄送的通知

  checkIn : (object) ->
    #pad.checkIn(object) #api 未修正
    db.checkIn(object)
    main.busy()

  buildUp : (status) ->
    main.busy()
    if status  # 收到桌子的狀態
      pad.buildUp(status)
      db.buildUp(

      ) #要進行新增資料
      db.lookUp(status)

    else #代表點選按鈕
      pad.buildUp()
      db.buildUp()
      @building = true #等待桌子建造的狀態

  lookUp : (object_type, object ) -> #main.lookup
    main.busy()
    db.lookUp(
      behavior : "LOOK_UP"
      object_type : object_type
      object : object
      , (data)->
        console.log(data,"pushed")
      )

  _lastMoveTime = undefined

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


class Pad

  $body = $ "body"
  $notiContainer = $ "#notiContainer"

  constructor : (@id) ->
    $(document).on 'click','.noti span.close',(e)->
      $(this).closest('.noti').addClass('animated fadeOutLeft')

    $(document).one 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend','.fadeOutLeft',(e)->
      console.info($(this))
      $(this).remove();

    @$infoA = $ "#INFO_A"
    @$infoB = $ "#INFO_B"
    @$infoC = $ "#INFO_C"



  noti : (noti) ->
    console.log("apd",noti);

    noti.appendTo("#notiContainer")
    setTimeout(()->
      noti.addClass('animated fadeOutLeft')
    , NOTI_TIME)

  navTo : (padObject) ->
    $(".active").not(padObject).fadeOut()
    padObject.fadeIn () ->
      padObject.addClass("active")

  currentPage : () ->
    a = $(".Page.active")
    console.log a.length , a
    a

class Notification
  _getNotiStructure = (noti) ->
    """
      <a class="noti animated fadeInLeft">
        <p class="#{noti[1]}" data-ajax="#{noti[2]}">#{noti[0]}</p>
        <span class='close'></span>
      </a>
    """

  constructor : (status) ->
    status.objString = @object2String (status)
    status.href = @getHref(status)
    data = @getData(status)
    console.log data
    jq = $ _getNotiStructure(data)
    $.extend @ , jq



    #[通知內文, class , href ]

  getData : (status)->
    id = status.device
    url = status.href
    padStr = """<span class="#{id}">平板#{id}</span>"""

    switch status.event
      when "otherPad.building"
      then ["#{padStr}正在建造中！", "noClick" , url]
      when "otherPad.buildUp"
      then ["#{padStr}建造了！" , "from#{id} toLookUp building" , url]

      when "otherPad.liveIn"
      then ["#{padStr}入住了#{status.objString}" , "from#{id} toLookUp building" , url]

      when "otherPad.lookUp.building"
      then ["#{padStr}正在查找#{status.objString}" , "from#{id} toLookUp building" , url ]
      when "otherPad.lookUp.type"
      then ["#{padStr}正在查找#{status.objString}" , "from#{id} toLookUp type" , url ]
      when "otherPad.lookUp.tag"
      then ["#{padStr}正在查找#{status.objString}" , "from#{id} toLookUp tag" , url ]

      when "desk.lookUp"
      then ["<span class='D'>共構桌</span>上選取了#{status.objString}", "from#{id} toLookUp building" , url ]
      when "desk.building" # 回傳建造資料的時候
      then ["<span class='D'>共構桌</span>上有一筆建造#{status.objString}", "from#{id} toLookUp building" , url ]

      when "unsolve"
      then ["有一筆未處理的狀態來自#{id}", "from#{id} " , url ]
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

this.Pad = Pad
this.Notification = Notification
this.DB = DB
this.Controller = Controller
this.Status = Status
