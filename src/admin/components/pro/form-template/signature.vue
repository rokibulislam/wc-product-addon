<template>
  <div class="fields">
    <div id="signature-container">
      <div class="signature-pad">
        <canvas
          id="signature-pad"
          class="signature-pad"
          style="border: 1px solid; border-width: thin;"
          width="200"
          height="100"
        ></canvas>
      </div>
      <input type="hidden" id="signature_image" name="signature_image" value />
      <div>
        <a href="#" id="clear">Clear</a>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
import form_field from "../../../mixin/form-field.js";
export default {
  name: "form_signature_field",
  mixins: [form_field],
};
</script>
