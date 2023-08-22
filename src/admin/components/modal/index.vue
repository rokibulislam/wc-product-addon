<template>
    <div>
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header">
                <slot name="header">
                    <button class="modal-default-button" @click="$emit('close')">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </slot>
              </div>

              <div class="modal-body">
                <slot name="body"> </slot>
              </div>

              <div class="modal-footer">
                <slot name="footer">

                </slot>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
</template>

<script type="text/javascript">
    export default {
        name: 'modal'
    }
</script>
<style type="text/css" scoped>

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 500px;
  margin: 0px auto;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
  padding: 20px;
}

.modal-header h4 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 5px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}


.modal-body > textarea {
    width: 100%;
    margin-bottom: 20px;
    height: 150px;
}

.modal-footer h4 {
    margin: 0;
}
.modal-footer {
    /* width: 100%;
    background: #f1f1f1;
    padding: 15px;
    box-sizing: border-box;
    display: flex;
    justify-content: flex-end; */
}
</style>
