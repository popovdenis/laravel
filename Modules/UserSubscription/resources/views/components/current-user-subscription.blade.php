@vite('Modules/UserSubscription/resources/assets/js/app.jsx')
<div id="current-user-subscription"
     data-props='@json([
        "subscriptionPlan" => $subscriptionPlan,
        "editUrl" => route("subscription::show"),
     ])'>
</div>
