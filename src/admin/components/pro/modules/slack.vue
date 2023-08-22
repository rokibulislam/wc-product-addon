<template>
<div>
    <div class="contactum-int-form-row">
        <div class="contactum-int-field-label">
            <label for="slack-list-id"> Slack Webhook URL </label>
        </div>
        <div class="contactum-int-field">
            <input type="url" class="regular-text"  placeholder="https://hooks.slack.com/services/...">
            <p class="help"> Slack webhook URL to send our JSON payloads View documentation
                <a href="https://api.slack.com/incoming-webhooks" target="_blank" ></a>
            </p>
        </div>
    </div>
</div>
</template>

<script type="text/javascript">
    import integration_mixin from "../../../mixin/integration.js";
    export default {
        name: "slack",
        mixins: [integration_mixin],
    };
</script>
