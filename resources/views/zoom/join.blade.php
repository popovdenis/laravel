<!DOCTYPE html>
<html>
<head>
    <title>Zoom Meeting</title>

    <!-- Dependencies for client view and component view -->
    <script src="https://source.zoom.us/3.12.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/lodash.min.js"></script>
    <!-- CDN for client view -->
    <script src="https://source.zoom.us/zoom-meeting-3.12.0.min.js"></script>
    <!-- CDN for component view -->
    <script src="https://source.zoom.us/zoom-meeting-embedded-3.12.0.min.js"></script>
</head>
<body>

<div id="zmmtg-root" style="width: 600px; height: 400px;"></div>

<script>
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

    const client = ZoomMtgEmbedded.createClient();
    let meetingSDKElement = document.getElementById('zmmtg-root');
    client.init({ zoomAppRoot: meetingSDKElement, language: 'en-US' });

    fetch(`/profile/zoom/signature?meetingNumber=${meetingConfig.meetingNumber}&role=${meetingConfig.role}`)
    .then(res => res.json())
    .then(data => {
        client.join({
            sdkKey: data.sdkKey,
            signature: data.signature, // role in SDK signature needs to be 1
            meetingNumber: meetingConfig.meetingNumber,
            password: meetingConfig.password,
            userName: meetingConfig.userName,
            // zak: zakToken // the host's zak token,
        })
    });
</script>

</body>
</html>
