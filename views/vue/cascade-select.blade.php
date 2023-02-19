<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

<script id="c-cascade-select" type="x-template">
    <div ref="root">
        <div class="form-group row mb-2"
            v-for="(items, i) of lists" :key="items"
            :class="[opt.horizontal ? (opt.horizontalColWidth || 'col') : '']"
            :data-level="i"
        >
            <label :for="getId(i)"
                class="c-cascade-select__label mb-2"
                :class="opt.labelWidth || 'col-md-3'">
                @{{ getLabel(i) }}
            </label>

            <div class="col c-cascade-select__input">
                <select :id="getId(i)" :disabled="!canModify"
                    class="form-select custom-select"
                    ref="selects"
                    @change="onChange(i, $event)"
                >
                    <option value="">
                        @{{ getPlaceholder(i) }}
                    </option>
                    <option :value="item[opt.valueField]"
                        v-for="item of items" :key="item[opt.valueField]"
                        :selected="isSelected(i, item)"
                    >
                        @{{ item[opt.textField] }}
                    </option>
                </select>
            </div>
        </div>

        <input :name="name" type="hidden" :value="getFinalValue()" />
    </div>
</script>

<script>
    function cascadeSelect() {
      return {
        template: '#c-cascade-select',
        props: {
          options: {
            type: Object,
            default() {
              return {};
            }
          },
          selectAttrs: {
            type: Object,
            default() {
              return {};
            }
          },
          modelValue: Array,
          name: String,
        },
        data() {
          return {
            opt: {},
            lists: [],
            ajaxUrl: '',
            values: [],
            canModify: true,
            loading: false,
          };
        },
        created() {
          this.opt = Object.assign(
            {
              id: 'cascade-select-' + u.uid(),
              selected: '',
              ignoreSelf: null,
              placeholder: '- Select -',
              placeholders: [],
              ajaxUrl: '',
              ajaxValueField: 'value',
              source: [],
              labels: [],
              labelWidth: 'col-md-3',
              fieldWidth: 'col',
              readonly: false,
              disabled: false,
              valueField: 'id',
              textField: 'title',
              horizontal: null,
              horizontalColWidth: null,
              defaultValue: '',
              onSelectInit: () => {},
              onChange: () => {},
              onValueInit: () => {},
            },
            this.options
          );

          this.init();
        },
        async mounted() {
          this.el = this.$refs.root;

          await this.prepareValues();
        },
        methods: {
          init() {
            this.canModify = !this.opt.readonly && !this.opt.disabled;
            this.ajaxUrl = this.opt.ajaxUrl;
          },

          async prepareValues() {
            if (this.loading) {
              return;
            }

            this.loading = true;

            this.lists = [];

            let values = this.modelValue.slice().map(String);

            this.values = [...values];

            if (values.length === 0) {
              values = [null];
            } else {
              values.unshift(null);
            }

            let promise = Promise.resolve();
            let lastValue;

            for (let i in values) {
              const v = values[i];
              const list = await this.loadItems(v, i);

              if (list.length > 0) {
                this.lists.push(list);
              }

              lastValue = v;
            }

            this.valueInit(this.$refs.root, lastValue, values);

            this.loading = false;

            this.$nextTick(() => {
              this.selectInit(this.$refs.selects[0]);
            });
          },

          reset() {
            this.prepareValues();
          },

          getLabel(i) {
            return this.opt.labels[i] || `Level ${i + 1}`;
          },

          getId(i) {
            return `${this.opt.id}__level-${i}`
          },

          getListValue(i) {
            return this.values[i] || '';
          },

          isSelected(i, item) {
            return String(this.getListValue(i)) === String(item[this.opt.valueField]);
          },

          getFinalValue() {
            const values = this.values.slice();

            if (values.length === 0) {
              return this.opt.defaultValue;
            }

            const v = values
              .filter(v => v != null)
              .filter(v => v !== '')
              .pop();

            if (v == undefined) {
              return this.opt.defaultValue;
            }

            return v;
          },

          getLevel() {
            return this.values.length;
          },

          onChange(i, event) {
            const el = event.target;

            this.values[i] = el.value;

            this.opt.onChange(event);

            event.stopPropagation();

            const changeEvent = new CustomEvent('change', {
              detail: {
                el,
                component: this,
                value: el.value,
                path: this.values
              }
            });

            this.el.dispatchEvent(changeEvent);

            this.$emit('change', changeEvent);
            this.$emit('update:modelValue', this.values);

            if (el.value === '') {
              // Clear child
              this.lists.splice(i + 1);
              this.values.splice(i + 1);
              return;
            }

            // Get child list
            this.loadItems(el.value, i)
              .then((list) => {
                // Clear child
                this.lists.splice(i + 1);
                this.values.splice(i + 1);

                if (list.length > 0) {
                  this.lists.push(list);

                  this.$nextTick(() => {
                    this.selectInit(this.$refs.selects[this.$refs.selects.length - 1]);
                  });
                }
              });
          },

          loadItems(parentId, i) {
            // Ajax
            if (this.ajaxUrl) {
              return u.$http.get(
                this.ajaxUrl,
                {
                  params: {
                    [this.opt.ajaxValueField]: parentId,
                    self: this.opt.ignoreSelf || null
                  }
                }
              ).then((res) => res.data.data);
            }

            // Source
            if (parentId) {
              return Promise.resolve(
                this.handleSourceItems(
                  this.findFromList(this.lists[i - 1] || [], parentId)?.children || []
                )
              );
            }

            return Promise.resolve(this.handleSourceItems(this.opt.source));
          },

          valueInit($select, value, path) {
            const event = new CustomEvent('value.init', {
              detail: {
                el: $select,
                component: this,
                value,
                path
              }
            });

            this.el.dispatchEvent(event);
          },

          selectInit($select) {
            const event = new CustomEvent('select.init', {
              detail: {
                el: $select,
                component: this,
              }
            });

            this.opt.onSelectInit(event);

            this.el.dispatchEvent(event);
          },

          handleSourceItems(items) {
            return items.map(item => {
                return {
                  [this.opt.valueField]: item.value[this.opt.valueField],
                  [this.opt.textField]: item.value[this.opt.textField],
                  children: item.children
                };
              })
              .filter(item => {
                if (this.opt.ignoreSelf) {
                  return item[this.opt.valueField] != this.opt.ignoreSelf;
                }

                return item;
              });
          },

          findFromList(items, value) {
            const found = items.filter(item => item[this.opt.valueField] == value);

            return found.shift();
          },

          getPlaceholder(i) {
            if (this.opt.placeholders[i]) {
              return this.opt.placeholders[i];
            }

            return this.opt.placeholder;
          }
        },
        watch: {
          modelValue(v) {
            if (v.length === 0) {
              this.reset();
            }
          }
        }
      };
    }
</script>
