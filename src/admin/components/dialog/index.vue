<template>
<div>    
    {{  dialogVisible  }}
    <el-dialog
        title="Rename Form"
        :visible.sync="dialogVisible"
        width="30%"
        :before-close="handleClose">

        <label for=""> Form Title </label>
        <el-input v-model="title" />

        <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">Cancel</el-button>
            <el-button type="primary" @click="dialogVisible = false">Confirm</el-button>
        </span>
    </el-dialog>
</div>
</template>

<script>
  export default {
    name: "RokDiaglog",
    props: ['title','dialogVisible'],
    methods: {
      handleClose(done) {
        this.$confirm('Are you sure to close this dialog?')
          .then(_ => {
            done();
          })
          .catch(_ => {});
      }
    }
  };
</script>