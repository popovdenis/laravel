<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zoom Meeting</title>

    <!-- Tailwind CDN for layout -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Zoom SDK dependencies -->
    <script src="https://source.zoom.us/3.12.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.12.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-3.12.0.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-embedded-3.12.0.min.js"></script>
    <script src="https://widget-js.cometchat.io/v3/cometchatwidget.js"></script>
</head>
<body class="h-screen overflow-hidden">

<div class="flex h-screen overflow-hidden">
    <div id="zmmtg-root" class="grow bg-gray-100"></div>

    <div class="w-[400px] h-full overflow-y-auto border-l p-2 bg-white">
        <x-comet-chat-widget :group-id="'schedule-' . $schedule->id" />
    </div>
</div>

<script>
    const meetingConfig = {
        meetingNumber: {{ $meetingNumber }},
        password: "{{ $password }}",
        userName: "{{ $userName }}",
        userEmail: "{{ $userEmail }}",
        role: {{ auth()->user()->hasRole('Teacher') ? 1 : 0 }},
        lang: "en-US",
        signature: "",
        china: false,
    };
    const zoomRoot = document.getElementById('zmmtg-root');
    const width = zoomRoot.clientWidth;
    const height = zoomRoot.clientHeight - 90;
    const client = ZoomMtgEmbedded.createClient();

    client.init({
        zoomAppRoot: zoomRoot,
        language: 'en-US',
        customize: {
            video: {
                isResizable: true,
                viewSizes: {
                    default: {
                        width,
                        height,
                    },
                },
            }
        }
    });

    fetch(`/profile/zoom/signature?meetingNumber=${meetingConfig.meetingNumber}&role=${meetingConfig.role}`)
    .then(res => res.json())
    .then(data => {
        client.join({
            sdkKey: data.sdkKey,
            signature: data.signature,
            meetingNumber: meetingConfig.meetingNumber,
            password: meetingConfig.password,
            userName: meetingConfig.userName,
        });
    });
</script>

</body>
</html>
