<script  setup>

import { ref, watch } from 'vue';
import { useTeacherStore } from '../../../stores/store-teachers'
import VhFieldVertical from './../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useTeacherStore();

const batch_count_range = ref([0, 10])

watch(batch_count_range, (range) => {
    store.query.filter.batch_count_min = range[0]
    store.query.filter.batch_count_max = range[1]
})

</script>

<template>
    <div class="col-3" v-if="store.show_advance_filters">

            <Panel class="is-small">

                <template class="p-1" #header>

                    <div class="flex flex-row">
                        <div >
                            <b class="mr-1">Advance Filters</b>
                        </div>
                    </div>
                </template>

                <template #icons>

                    <div class="p-inputgroup">

                        <Button data-testid="teachers-hide-filter"
                                class="p-button-sm"
                                @click="store.show_advance_filters = false">
                            <i class="pi pi-times"></i>
                        </Button>

                    </div>

                </template>

            <VhFieldVertical >
                <template #label>
                    <b>Subjects:</b>
                </template>

                <div class="flex gap-1">
                    <Dropdown v-model="store.query.filter.subject" 
                            :options="store.assets.subjects"
                            data-testid="teachers-filters-subject-only"
                            filter optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Subject" 
                            class="w-full md:w-14rem" />
                    <!-- <label for="trashed-exclude" class="cursor-pointer">Subjects</label> -->
                    <Button label="Reset" @click.prevent="store.query.filter.subject = null"/>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Genders:</b>
                </template>

                <div>
                <Dropdown v-model="store.query.filter.gender" 
                        :options="store.assets.genders" 
                        filter optionLabel="name" 
                        optionValue="id"
                        data-testid="teachers-filters-gender-only"  
                        placeholder="Select a Gender" 
                        class="w-full md:w-14rem">
                </Dropdown>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Batches:</b>
                </template>

                <div class="flex gap-1">
                    <Dropdown v-model="store.query.filter.batches" 
                            :options="store.assets.batches"
                            data-testid="teachers-filters-batch-only"
                            filter optionLabel="name"
                            optionValue="name"
                            placeholder="Select a batch" 
                            class="w-full md:w-14rem" />
                    <!-- <label for="trashed-exclude" class="cursor-pointer">Subjects</label> -->
                    <Button label="Reset" @click.prevent="store.query.filter.batches = null"/>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Batch Count Range:</b>
                </template>

                <div>
                    <div class="mb-3 p-2">
                        <Slider
                            v-model="batch_count_range"
                            range
                            :min="0"
                            :max="50"
                            :step="1"
                            class="w-full"
                        />
                        <div class="text-sm mt-1 text-gray-500">
                            {{ batch_count_range[0] }} - {{ batch_count_range[1] }}
                        </div>
                    </div>
                </div>

            </VhFieldVertical>

        </Panel>

    </div>
</template>
