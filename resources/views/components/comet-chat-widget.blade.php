<div id="cometchat-container" style="height: 100%; width: 100%"></div>
<script>
    window.addEventListener('DOMContentLoaded', function (event) {
        const uid = "user-{{ auth()->id() }}";
        const groupId = "{{ $groupId }}";

        CometChatWidget.init({
            "appID": "{{ config('services.cometchat.app_id') }}",
            "appRegion": "{{ config('services.cometchat.region') }}",
            "authKey": "{{ config('services.cometchat.auth_key') }}"
        }).then(response => {
            console.log("Initialization completed successfully");
            CometChatWidget.login({
                "uid": uid
            }).then(response => {
                CometChatWidget.launch({
                    "widgetID": '{{ config('services.cometchat.widget_id') }}',
                    "target": "#cometchat-container",
                    "roundedCorners": "true",
                    "height": '100%',
                    "width": '100%',
                    "defaultID": groupId, //default UID (user) or GUID (group) to show,
                    "defaultType": 'group' //user or group
                });
            }, error => {
                console.log("User login failed with error:", error);
            });
        }, error => {
            console.log("Initialization failed with error:", error);
        });
    });
</script>
