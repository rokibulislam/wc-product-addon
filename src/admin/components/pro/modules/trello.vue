<template>
    <div>
        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="trello-list-id"> Board <span class="required">*</span></label>
                <a href="#" @click.prevent="updateBoards($event.target)" class="contactum-integration-updater">
                    <span class="dashicons dashicons-update"></span></a>
            </div>
            <div class="contactum-int-field">
                <select  id="trello-list-id" @change="updateLists();updateMembers();">
                    <option value=""> &mdash; Select List &mdash; </option>
                    <option v-for="board in boards" :value="board.id">{{ board.name }}</option>
                </select>
                <span class="description"> Select your trello board for subscription </span>
            </div>
        </div>

        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="trello-list-id"> List <span class="required">*</span></label>
                <a href="#" @click.prevent="updateLists($event.target)" class="contactum-integration-updater">
                    <span class="dashicons dashicons-update"></span>
                </a>
            </div>
            <div class="contactum-int-field">
                <select  id="trello-list-id">
                    <option value=""> &mdash; Select List &mdash; </option>
                    <!-- <option v-for="list in lists[settings.board]" :value="list.id">{{ list.name }}</option> -->
                </select>

                <span class="description"> Select your trello list for subscription </span>
            </div>
        </div>

        <div class="contactum-int-form-row">
            <div class="contactum-int-field-label">
                <label for="trello-list-id"> Member </label>
                <a href="#" @click.prevent="updateMembers($event.target)" class="contactum-integration-updater">
                    <span class="dashicons dashicons-update"></span>
                </a>
            </div>
            <div class="contactum-int-field">
                <p class="description"> Select trello Members to assign the cards </p>
            </div>
        </div>


        <fieldset>
            <legend> Mapping Fields </legend>

            <p class="description" style="padding: 0 0 10px 0;">
                 Please map the form input fields with trello required fields
            </p>

            <div class="contactum-int-form-row mapping-fields">
                <div class="contactum-int-field-label">
                    <label for="trello-list-id"> Card Name <span class="required">*</span></label>
                </div>
                <div class="contactum-int-field">
                    <div class="contactum-int-field-small">
                        <input type="text" class="regular-text">
                        <merge_tags @insert="insertValue" field="title"></merge_tags>
                    </div>
                </div>
            </div>

            <div class="contactum-int-form-row">
                <div class="contactum-int-field-label">
                    <label for="trello-list-id"> Card Description </label>
                </div>
                <div class="contactum-int-field">
                    <div class="contactum-int-field-small">
                        <textarea class="large-text" rows="5"></textarea>
                        <merge_tags @insert="insertValue" field="description"></merge_tags>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
import merge_tags from "../../merge-tags/index.vue";
export default {
  name: "trello",
  components: {
    merge_tags
  },
  data: function() {
    return {
       lists: [],
        boards: [],
        members: []
    };
  },
  computed: {},
  created: function() {
    this.getBoards();
  },
  methods: {
    getLists: function() {},
    updateLists: function() {},
    getBoards: function() {},
    updateBoards: function() {},
    getMembers: function() {},
    updateMembers: function() {},
    insertValue: function insertValue(type, field, property) {}
  }
};
</script>
