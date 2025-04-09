<!DOCTYPE html>
<html>
<head>
    <title>Zoom Meeting</title>

    <link rel="stylesheet" href="https://source.zoom.us/3.12.0/css/bootstrap.css" />
    <link rel="stylesheet" href="https://source.zoom.us/3.12.0/css/react-select.css" />
    <link rel="stylesheet" href="https://source.zoom.us/3.12.0/zoom-meeting-3.12.0.css" />

    <script src="https://source.zoom.us/3.12.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/zoom-meeting-3.12.0.min.js"></script>

    <style>
        html, body, #zmmtg-root {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div id="zmmtg-root"></div>

<script>
    // click join meeting button
    const meetingConfig = {
        meetingNumber: {{ $meetingNumber }},
        password: "{{ $password }}",
        userName: "{{ $userName }}",
        userEmail: "{{ $userEmail }}",
        role: 0, // 0 — participant, 1 — host
        lang: "en-US",
        signature: "",
        china: false,
    };
    ZoomMtg.setZoomJSLib('https://source.zoom.us/3.12.0/lib', '/av');
    ZoomMtg.preLoadWasm();

    fetch(`/profile/zoom/signature?meetingNumber=${meetingConfig.meetingNumber}&role=${meetingConfig.role}`)
    .then(res => res.json())
    .then(data => {
        ZoomMtg.init({
            leaveUrl: meetingConfig.leaveUrl,
            success: function () {
                ZoomMtg.join({
                    signature: data.signature,
                    sdkKey: data.sdkKey,
                    meetingNumber: meetingConfig.meetingNumber,
                    passWord: meetingConfig.password,
                    userName: meetingConfig.userName,
                    userEmail: meetingConfig.userEmail,
                    success: function (res) {
                        console.log('JOIN SUCCESS', res);
                    },
                    error: function (err) {
                        console.error('JOIN ERROR', err);
                    }
                });
            }
        });
    });
</script>

</body>
</html>
