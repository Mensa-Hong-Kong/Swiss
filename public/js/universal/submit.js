const token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
submitForm = function( formData, action, successCallback = "", failCallback = "", method = "POST" ) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if ( xmlHttp.readyState == 4 ) {
            // console.log("ready");
            // console.log(this.getResponseHeader('content-type'));
            closeLoader();
            if( xmlHttp.status == 200 ) {
                var msgs = JSON.parse(this.responseText);
                if( Array.isArray( msgs ) ) {
                    alertMessage = "";
                    msgs.forEach(
                        function(msgs, index, array){
                            // console.log(errorMsg.input);
                            // console.log(errorMsg.message);
                            if( failCallback == "" ) {
                                if( input = document.getElementById( msgs.type ) ) {
                                    if( msgs.type != "msg" ) {
                                        showError( input, msgs.message );
                                    } else {
                                        alertMessage += msgs.message + "\r\n";
                                    }
                                } else {
                                    logoutTimer.restart( msgs.time );
                                }
                            } else {
                                failCallback( msgs );
                                logoutTimer.restart( msgs.time );
                            }
                        }
                    );
                    if( alertMessage != "" ) {
                        alert( alertMessage );
                    }
                } else {
                    // console.log( msgs.url );
                    if( msgs.type == "success" ) {
                        if( successCallback == "" ) {
                            window.location = msgs.url;
                        } else {
                            successCallback( msgs );
                            logoutTimer.restart( msgs.time );
                        }
                    } else {
                        if( failCallback == "" ) {
                            alert( msgs.message );
                            logoutTimer.restart( msgs.time );
                        } else {
                            failCallback( msgs );
                            logoutTimer.restart( msgs.time );
                        }
                    }
                }
                // console.log(errorMsgs);
            } else {
                switch( xmlHttp.status ) {
                    case 503:
                        message = "Alta Staff System or aisystem DB connect fail, please try again later, or contact I.T.";
                        break;
                    case 500:
                        message = "Alta Staff System or aisystem DB connect fail, please try again later, or contact I.T.";
                        break;
                    case 419:
                        message = "跨站請求偽造警告：如長時間沒停留本頁，請刷新頁面，否則，請注意網址是否有異常。";
                        window.location = "/";
                        break;
                    case 403:
                        message = "Sorry, you have no permission";
                        break;
                    case 401:
                        message = "Sorry, you are unauthorized";
                        break;
                }
                alert( message );
            }
        }
    }
    console.log( method );
    xmlHttp.open( method.toUpperCase(), action, true );
    if( method.toUpperCase() != "GET" ) {
        xmlHttp.setRequestHeader( "X-CSRF-TOKEN", token );
    }
    openLoader();
    xmlHttp.send( formData );
}
