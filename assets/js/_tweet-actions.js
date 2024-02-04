const API_URL = 'http://www.yamagata3.shop/twitter/tweet/'

function ajaxAction (url, data) {
  var deferred = new $.Deferred()
  $.ajax({
    url: API_URL + url,
    type: 'POST',
    data: data,
    cache: false,
  }).then(
    function (data) {
      deferred.resolve()
    },
    //失敗処理
    function (data) {
      deferred.rejected()
    },
  )
  return deferred
}

const toggleLike = (userId, tweetId, isActioned) => {
  const url = isActioned ? 'removeLike' : 'addLike'
  let isSuccess = true
  const ajax = ajaxAction(
    url,
    {
      current_user_id: userId,
      tweet_id: tweetId,
    },
  ).fail(function (data) {
    isSuccess = false
    alert('エラーが発生しました。')
  })

  return isSuccess
}

const toggleReTweet = (userId, tweetId, isActioned) => {
  const url = isActioned ? 'removeReTweet' : 'addReTweet'
  let isSuccess = true
  const ajax = ajaxAction(
    url,
    {
      current_user_id: userId,
      tweet_id: tweetId,
    },
  ).fail(function (data) {
    isSuccess = false
    alert('エラーが発生しました。')
  })

  return isSuccess
}

$('.p-tweet__button').on('click', function (event) {
  const buttonType = $(this).data('button-type')
  const isActioned = $(this).hasClass('p-tweet__button--is-liked') ||
    $(this).hasClass('p-tweet__button--is-retweeted')
  const tweetId = $(this).closest('.p-tweet').data('tweet-id')

  let isSuccess = true
  if (buttonType === 'like') {
    isSuccess = toggleLike(CURRENT_USER_ID, tweetId, isActioned)
  } else if (buttonType === 'retweet') {
    isSuccess = toggleReTweet(CURRENT_USER_ID, tweetId, isActioned)
  }

  if(isSuccess) {
    let toggleClass = buttonType === 'like' ? 'p-tweet__button--is-liked' : 'p-tweet__button--is-retweeted'
    const counterDom = $(this).next()
    const count = Number(counterDom.text())
    const calcNum = isActioned ? -1 : 1

    $(this).toggleClass(toggleClass)
    counterDom.text(count + calcNum)
  }
  return false;
})
