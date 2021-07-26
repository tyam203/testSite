$(function(){
    $('.form').submit(function(){
        var checkInDate = $('.checkInDate').val();
        if(checkInDate == '') {
            alert('チェックイン日を選択してください');
            return false;
        }
    })
})