class INFO_A
  constructor : () ->
    @dom = $("#INFO_A");
    @typeDom = $(".infoABox") ;
    @tagDom = $(".infoABox")

    .textTypingL # 建築 名稱

    .infoABoxBottom  # 類別 標籤資訊

    .infoAContainer # 建築 內文
      .infoABuildingName
      .infoABuildingTypeText
      .infoABuildingContext
      .infoAtagBox
        .infoAtag

    .infoBBuildingContent # 類別 圖片
  showAinfo : () ->

  showTop3Type : (json) ->  # { type : times }
    for obj , i  in json
      $(@dom).find(".type")[i].html()

  showTop3Tag : (json) ->
    for obj , i  in json
      $(@dom).find(".tag")[i].html()



class INFO_B
  constructor : () ->

  show10Type : () ->
  showTop3Tag : () ->


class INFO_C
  constructor : () ->
  showCinfo : () ->
  showCelements : () ->
