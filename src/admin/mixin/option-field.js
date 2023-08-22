export default {
	props: {
		field: {
			type: Object,
			default() {
				return {};
			}
		},

		// editingfield: {
        editfield: {
			type: Object,
			default() {
				return {};
			}
		}
	},

    computed: {
        // editfield: function() {
        //     return this.$store.getters.editfield;
        // }
    }
};
