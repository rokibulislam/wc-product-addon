<template>
  <div class="ff_photo_card">
    <div class="wpf_photo_holder">
      <img style="max-width: 100%" v-if="image_url" :src="image_url" />
      <div @click="initUploader" class="photo_widget_btn">
        <span class="dashicons dashicons-cloud-upload"></span>
      </div>

      <div
        @click="image_url = ''"
        v-if="enable_clear_name == 'yes' && image_url"
        class="photo_widget_btn_clear"
      >
        <span class="dashicons dashicons-trash"></span>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
import option_field from "../../mixin/option-field.js";
export default {
  name: "photo_widget",
  props: ["value", "design_mode", "enable_clear"],
  mixins: [option_field],
  data() {
    return {
      image_url: this.value,
      enable_clear_name: this.enable_clear,
    };
  },
  watch: {
    image_url() {
      this.$emit("input", this.image_url);
    },
  },
  methods: {
    initUploader(e) {
      e.preventDefault();
      if (typeof wp !== "undefined" && wp.media && wp.media.editor) {
        var that = this;
        var send_attachment_bkp = wp.media.editor.send.attachment;
        wp.media.editor.send.attachment = function (props, attachment) {
          console.log("props", props);
          console.log("attachment", attachment);
          that.image_url = attachment.url;
          wp.media.editor.send.attachment = send_attachment_bkp;
        };
        wp.media.editor.open();
      }
      return false;
    },
  },
  mounted() {},
};
</script>
