<template>
    <div class="merge-tag-wrapper">
        <a href="#" @click.prevent="isHidden = !isHidden" class="mergetag_link"> <span class="dashicons dashicons-editor-code"></span> </a>
        <div class="merge-tags" v-if="!isHidden">
            <template v-if="!fieldsonly">
            <el-card>
                <ul>
                    <li v-for="smarttag in smart_tags"> <h4> {{ smarttag.title }} </h4>
                        <ul v-if="smarttag.tags">
                            <li v-for="(tag,index)  in smarttag.tags" @click.prevent="insertField(index)"> {{ tag }} </li>
                        </ul>
                    </li>
                </ul>
            </el-card>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        name: 'merge_tags',
        props: {
            field: [String, Number,Object],
            filter: {
                type: String,
                default: null
            },
            fieldsonly: {
                type: Boolean,
                default: false
            }
        },
        data: function() {
            return {
                isHidden: true
            }
        },
        computed: {
            smart_tags: function() {
                return window.contactum.smart_tags;
            }
        },
        methods: {
            toggleFields: function(event) {

            },
            insertField: function(type,field) {
                this.$emit('insert', type,field,this.field);
            }
        }
    }
</script>

<style type="text/css">
.mergetag_link{
    position: absolute;
    right: 5px;
    top: 30px;
    color: #999;
}

.merge-tag-wrapper .merge-tags {
    max-height: 110px;
    overflow: hidden;
    overflow-y: scroll;
    border: 1px solid #e5e5e5;
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.04);
    display: block;
    position: absolute;
    right: 0px;
    top: 60px;
    background: #fff;
    z-index: 1000;
    min-height: 150px;
}

.merge-tags ul {
    /* padding: 5px; */
}

.merge-tags ul li ul {
    /* padding-left :5px; */
}

.merge-tags ul li ul {
    /* padding-left: 5px; */
}

.merge-tags ul li h4 {
    padding: 0;
    margin: 0;
    background: #f0f0f0;
    width: 100%;
    padding: 2px;
    text-align: center;
}

.merge-tags ul li ul li {
    cursor: pointer;
}

</style>
