@localhost = "140.119.134.100" ;
# @localhost = "192.168.1.100" if false

class SwitchController
  statusController = undefined
  constructor : (_statusController) ->
    statusController = _statusController

  prepareStatus : (behavior , object_type ) ->
    obj =
      behavior : behavior
      object_type : object_type
  ajaxPage : (href,status) ->
    console.log(status);
    # alert ("ajaxIN")
    $.get "http://#{localhost}/co-construction/php/controller.php?#{href}", (data,textStatus) ->
      if textStatus isnt "success"
        alert ("get page failed")
      else if textStatus is "success"

        $curPage = $ '.Page.active'
        $newPage = $("<div class='Page' style='opacity:0.0'>#{data}</div>").remove 'video'
        $newPage = $newPage.appendTo '#pageContainer'
        # alert ("AJAXDONE")
        if (status isnt false)
          setTimeout ()->
            status.object = localStorage['status']
            statusController.addStatus(status)
          ,200

        setTimeout ()->
          # alert ("setTimeoutSTART")
          $curPage.animate { opacity: 0.0 }, 600 , ()->
            $curPage.remove()
            # alert ("fadeoutdone")
            $newPage.delay(100).animate({opacity: 1} ,600).promise().done () ->
              # alert("fadeindone")
              $newPage.addClass "active"

              effectAnimate() if typeof(effectAnimate)is "function"

        ,800
class ButtonListener
  switchController = undefined
  _main = undefined
  constructor: (_switchController) ->
    _main = this
    switchController = _switchController
    $(document).on "click",".toLookUp.building" , (e) ->
      e.preventDefault()
      status = switchController.prepareStatus("LOOK_UP","B")
      switchController.ajaxPage $(this).data('ajax'),status
      false
    $(document).on "click" , ".toLookUp.type" , (e) ->
      e.preventDefault()
      status = switchController.prepareStatus("LOOK_UP","T")
      switchController.ajaxPage $(this).data('ajax'),status
      false
    $(document).on "click" , ".toLookUp.tag" , (e) ->
      e.preventDefault()
      status = switchController.prepareStatus("LOOK_UP","G")
      switchController.ajaxPage $(this).data('ajax'),status
      false
    $(document).on "click" , ".noti" , (e) ->
      action = $(this).data("action") # action 還沒設定

    $(document).on "click" , ".toLookUp.tag" , (e) ->
      e.preventDefault()
      status = switchController.prepareStatus("LOOK_UP","G")
      switchController.ajaxPage $(this).data('ajax'), status
      false

    # toBuildUp toHouse  toFinish
    $(document).on "click" , ".toFinish" , (e)->
      e.preventDefault()
      switchController.ajaxPage $(this).data('ajax'), false


this.SwitchController = SwitchController
this.ButtonListener = ButtonListener


# for divac judgement
this.radian = true ;

$ ->
  pad = new Pad()
  db = new DB()

  statusController = new Controller(pad,db)
  switchController = new SwitchController(statusController)

  ButtonListener = new ButtonListener(switchController)
  statusController.track();
  # The first page.

  initStatus = switchController.prepareStatus("LOOK_UP","B")
  switchController.ajaxPage "page=infoA&bid=1" , initStatus
