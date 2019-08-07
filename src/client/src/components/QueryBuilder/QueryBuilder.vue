<template>

    <div class="vue-query-builder">

        <query-builder-group
            :index="0"
            :length="0"
            :query.sync="query"
            :ruleTypes="ruleTypes"
            :rules="mergedRules"
            :maxDepth="maxDepth"
            :depth="depth"
            :styled="styled"
            :labels="mergedLabels"
            :table="table"
            :columns="columns"
            type="query-builder-group"
        ></query-builder-group>

    </div>

</template>

<script>
    import QueryBuilderGroup from './components/QueryBuilderGroup.vue';
    import { deepClone } from './utilities.js';

    import VueJsonPretty from "vue-json-pretty";

    let defaultLabels = {

        matchType: "",

        matchTypes: [

            {
                id: "AND",
                label: "AND"
            },
            {
                id: "OR",
                label: "OR"
            },

        ],

        addRule: {
            text: "Rule",
            icon: "fas fa-plus",
        },
        removeRule: {
            text: "Delete",
            icon: "fas fa-times",
        },
        addGroup: {
            text: "Group",
            icon: "fas fa-plus-circle",
        },
        removeGroup: {
            text: "Delete",
            icon: "fas fa-times-circle",
        },
        textInputPlaceholder: "value",

        notifications: {
            sqlError: {
                text: "Missing values in this rule are preventing the generation of valid syntax for a SQL condition.",
                icon: "fas fa-exclamation-circle text-danger",
            },
            sqlSuccess: {
                text: "A valid SQL condition has been generated, using these values.",
                icon: "fas fa-check-circle text-success",
            }

        }

    };

    export default {
        name: 'QueryBuilder',

        components: {
            QueryBuilderGroup,
            VueJsonPretty,
        },

        props: {
            rules: Array,
            labels: {
                type: Object,
                default () {
                    return defaultLabels;
                }
            },
            styled: {
                type: Boolean,
                default: true
            },
            maxDepth: {
                type: Number,
                default: 3,
                validator: function (value) {
                    return value >= 1
                }
            },
            value: Object,
            table: {
                type: String,
                required: true,
                /*
                validator: function(value)
                {
                    return value !== "";
                }
                */
            },
            columns: Object,
        },

        data () {
            return {
                depth: 1,

                query: {
                    table: this.table,
                    logicalOperator: this.labels.matchTypes[0].id,
                    children: [],
                    sql: "",
                },

                ruleTypes: {
                    "text": {
                        operators: ['equals','does not equal','contains','does not contain','is empty','is not empty','begins with','ends with'],
                        inputType: "text",
                        id: "text-field"
                    },
                    "numeric": {
                        operators: ['=','<>','<','<=','>','>='],
                        inputType: "number",
                        id: "number-field"
                    },
                    "custom": {
                        operators: [],
                        inputType: "text",
                        id: "custom-field"
                    },
                    "radio": {
                        operators: [],
                        choices: [],
                        inputType: "radio",
                        id: "radio-field"
                    },
                    "checkbox": {
                        operators: [],
                        choices: [],
                        inputType: "checkbox",
                        id: "checkbox-field"
                    },
                    "select": {
                        operators: [],
                        choices: [],
                        inputType: "select",
                        id: "select-field"
                    },
                    "multi-select": {
                        operators: ['='],
                        choices: [],
                        inputType: "select",
                        id: "multi-select-field"
                    },
                }
            }
        },

        computed: {
            mergedLabels () {
                return Object.assign({}, defaultLabels, this.labels);
            },

            mergedRules () {
                var mergedRules = [];
                var vm = this;

                vm.rules.forEach(function(rule){
                    if ( typeof vm.ruleTypes[rule.type] !== "undefined" ) {
                        mergedRules.push( Object.assign({}, vm.ruleTypes[rule.type], rule) );
                    } else {
                        mergedRules.push( rule );
                    }
                });

                return mergedRules;
            }
        },

        mounted: function()
        {
            this.$watch(
                'query',
                newQuery => {
                    if (JSON.stringify(newQuery) !== JSON.stringify(this.value)) {
                        this.$emit('input', deepClone(newQuery));
                    }
                }, {
                    deep: true
                });

            this.$watch(
                'value',
                newValue => {
                    if (JSON.stringify(newValue) !== JSON.stringify(this.query)) {
                        this.query = deepClone(newValue);
                    }
                }, {
                    deep: true
                });

            if ( typeof this.$options.propsData.value !== "undefined" ) {
                this.query = Object.assign(this.query, this.$options.propsData.value);
            }

            this.$watch(
                'table',
                newValue => {
                    if (newValue !== this.query.table)
                    {
                        let updatedQuery = deepClone(this.query);
                        updatedQuery.table = newValue;
                        this.$emit('input', updatedQuery);
                    }
                }, {
                    deep: false
                });



        }
    }
</script>

<style>
    .vue-query-builder-styled .vqb-group .rule-actions {
        margin-bottom: 20px;
    }

    .vue-query-builder-styled .vqb-rule {
        margin-top: 15px;
        margin-bottom: 15px;
        background-color: #f5f5f5;
        border-color: #ddd;
        padding: 15px;
    }

    .vue-query-builder-styled .vqb-rule label {
        margin-right: 10px;
    }

    .vue-query-builder-styled .vqb-group.depth-1 .vqb-rule,
    .vue-query-builder-styled .vqb-group.depth-2 {
        border-left: 2px solid #8bc34a;
    }

    .vue-query-builder-styled .vqb-group.depth-2 .vqb-rule,
    .vue-query-builder-styled .vqb-group.depth-3 {
        border-left: 2px solid #00bcd4;
    }

    .vue-query-builder-styled .vqb-group.depth-3 .vqb-rule,
    .vue-query-builder-styled .vqb-group.depth-4 {
        border-left: 2px solid #ff5722;
    }

    .vue-query-builder-styled .close {
        opacity: 1;
        color: rgb(150,150,150);
    }

    @media (min-width: 768px) {
        .vue-query-builder-styled .vqb-rule.form-inline .form-group {
            display: block;
        }
    }
</style>
