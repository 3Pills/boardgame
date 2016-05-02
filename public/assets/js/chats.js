var baseURL = "./";

$(document).ready(function() {
    console.log($('meta[name="csrf-token"]').attr('content'));

    pullData();

    $(document).keyup(function(e) {
        if (e.keyCode == 13)
            sendMessage();
        else
            isTyping();
    });
});

function pullData() {
    retrieveChatMessages();
    retrieveTypingStatus();
    setTimeout(pullData,3000);
}

function retrieveChatMessages() {
    $.post(baseURL+'retrieveChatMessages', {user_id: user_id}, function(data) {
        if (data.length > 0)
            $('#chat-window').append('<br><div>'+data+'</div><br>');
    }).done(function(msg){
        console.log('retrieve chat sent');
    }).fail(function(xhr, status, errorThrown) {
        //alert(xhr.responseText);
    });
}

function retrieveTypingStatus() {
    $.post(baseURL+'retrieveTypingStatus', {user_id: user_id}, function(user_id) {
        if (user_id.length > 0)
            $('#typingStatus').html(user_id+' is typing');
        else
            $('#typingStatus').html('');
    }).done(function(msg){
        console.log('retrieve type sent');
    }).fail(function(xhr, status, errorThrown) {
        //alert(xhr.responseText);
    });
}

function sendMessage() {
    var text = $('#text').val();

    if (text.length > 0) {
        $.post(baseURL+'sendMessage', {text: text, user_id: user_id}, function() {
            $('#chat-window').append('<br><div style="text-align: right">'+text+'</div><br>');
            $('#text').val('');
            notTyping();
        }).done(function(msg){
            console.log('message sent');
        }).fail(function(xhr, status, errorThrown) {
            //alert(errorThrown);
            //var errors = data.responseJSON;
            //console.log(errors);
        });
    }
}

function isTyping() {
    $.post(baseURL+'isTyping', {user_id: user_id}).done(function(msg){
        console.log('type sent');
    }).fail(function(xhr, status, errorThrown) {
        //alert(xhr.responseText);
    });
}

function notTyping() {
    $.post(baseURL+'notTyping', {user_id: user_id}).done(function(msg){
        console.log('not type sent');
    }).fail(function(xhr, status, errorThrown) {
        //alert(xhr.responseText);
    });
}