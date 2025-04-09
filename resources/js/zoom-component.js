import ZoomMtgEmbedded from "@zoom/meetingsdk/embedded"

window.joinMeeting = function(config) {
    debugger;
    const client = ZoomMtgEmbedded.createClient();
    let meetingSDKElement = document.getElementById('meetingSDKElement')
    client.init({
        zoomAppRoot: meetingSDKElement,
        language: 'en-US',
        patchJsMedia: true
    }).then(() => {
        client.join({
            sdkKey: config.sdkKey,
            signature: config.signature,
            meetingNumber: config.meetingNumber,
            password: config.password,
            userName: config.userName
        }).then(() => {
            console.log('joined successfully')
        }).catch((error) => {
            console.log(error)
        })
    }).catch((error) => {
        console.log(error)
    });
};
