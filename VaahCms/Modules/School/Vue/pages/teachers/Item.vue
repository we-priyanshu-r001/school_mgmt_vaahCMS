<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {useRoute} from 'vue-router';

import { useTeacherStore } from '../../stores/store-teachers'

import VhViewRow from '../../vaahvue/vue-three/primeflex/VhViewRow.vue';
const store = useTeacherStore();
const route = useRoute();

const batchNames = computed(() => {
    return store.item.batches.map(batch => batch);
});

onMounted(async () => {

    /**
     * If record id is not set in url then
     * redirect user to list view
     */
    if(route.params && !route.params.id)
    {
        store.toList();
        return false;
    }

    /**
     * Fetch the record from the database
     */
    if(!store.item || Object.keys(store.item).length < 1)
    {
        await store.getItem(route.params.id);
    }

});

//--------toggle item menu
const item_menu_state = ref();
const toggleItemMenu = (event) => {
    item_menu_state.value.toggle(event);
};
//--------/toggle item menu

</script>
<template>

    <div class="col-6" >

        <Panel class="is-small" v-if="store && store.item">

            <template class="p-1" #header>

                <div class="p-panel-title w-7 white-space-nowrap
                overflow-hidden text-overflow-ellipsis">
                    #{{store.item.id}}
                </div>

            </template>

            <template #icons>


                <div class="p-inputgroup">

                    <Button label="Edit"
                            class="p-button-sm"
                            @click="store.toEdit(store.item)"
                            data-testid="teachers-item-to-edit"
                            icon="pi pi-save"/>

                    <!--item_menu-->
                    <Button
                        type="button"
                        class="p-button-sm"
                        @click="toggleItemMenu"
                        data-testid="teachers-item-menu"
                        icon="pi pi-angle-down"
                        aria-haspopup="true"/>

                    <Menu ref="item_menu_state"
                          :model="store.item_menu_list"
                          :popup="true" />
                    <!--/item_menu-->

                    <Button class="p-button-primary p-button-sm"
                            icon="pi pi-times"
                            data-testid="teachers-item-to-list"
                            @click="store.toList()"/>

                </div>



            </template>


            <div class="mt-2" v-if="store.item">

                <Message severity="error"
                         class="p-container-message"
                         :closable="false"
                         icon="pi pi-trash"
                         v-if="store.item.deleted_at">

                    <div class="flex align-items-center justify-content-between">

                        <div class="">
                            Deleted {{store.item.deleted_at}}
                        </div>

                        <div class="ml-3">
                            <Button label="Restore"
                                    class="p-button-sm"
                                    data-testid="teachers-item-restore"
                                    @click="store.itemAction('restore')">
                            </Button>
                        </div>

                    </div>

                </Message>

                <div class="p-datatable p-component p-datatable-responsive-scroll p-datatable-striped p-datatable-sm">
                <table class="p-datatable-table overflow-wrap-anywhere">
                    <tbody class="p-datatable-tbody">
                    <template v-for="(value, column) in store.item ">

                        <template v-if="column === 'created_by' || column === 'updated_by'
                        || column === 'deleted_by' || column == 'gender'
                        || column === 'subject' || column === 'batches_obj'">
                        </template>

                        <template v-else-if="column === 'id' || column === 'uuid'">
                            <VhViewRow :label="column"
                                       :value="value"
                                       :can_copy="true"
                            />
                        </template>

                        <template v-else-if="column === 'vh_taxonomy_subject_id'">
                            <VhViewRow label="Subject"
                                       :value="store.item.subject.name"
                                       :can_copy="true"
                            />
                        </template>

                        <template v-else-if="column === 'vh_taxonomy_gender_id'">
                            <VhViewRow label="Gender"
                                       :value="store.item.gender.name"
                                       :can_copy="true"
                            />
                        </template>

                        <template v-else-if="column === 'batches' && Array.isArray(value)">
                           <VhViewRow label="Batches" type="array">
                            <span
                                v-tooltip.bottom="store.item.batches_obj.map(b => b.name).join(', ')"
                                class="flex flex-wrap items-center"
                            >
                                <Tag
                                v-for="(batch) in store.item.batches.slice(0, 3)"
                                :key="batch.id"
                                :value="batch.name"
                                class="mr-1 mb-1"
                                />
                                <span v-if="store.item.batches.length > 3" class="text-gray-500 text-sm">
                                +{{ store.item.batches.length - 3 }} more
                                </span>
                            </span>
                            </VhViewRow>
                        </template>

                        <template v-else-if="(column === 'created_by_user' || column === 'updated_by_user'
                        || column === 'deleted_by_user') && (typeof value === 'object' && value !== null)">
                            <VhViewRow :label="column"
                                       :value="value"
                                       type="user"
                            />
                        </template>

                        <template v-else-if="column === 'is_active'">
                            <VhViewRow :label="column"
                                       :value="value"
                                       type="yes-no"
                            />
                        </template>

                        <template v-else>
                            <VhViewRow :label="column"
                                       :value="value"
                                       />
                        </template>


                    </template>
                    </tbody>

                </table>

                </div>
            </div>
        </Panel>

    </div>

</template>
