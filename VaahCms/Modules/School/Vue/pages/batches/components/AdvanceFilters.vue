<script  setup>

import { ref, watch } from 'vue';
import { useBatchStore } from '../../../stores/store-batches'
import VhFieldVertical from './../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useBatchStore();

const student_count_range = ref([0,10])
const teacher_count_range = ref([0,10])

watch(student_count_range, (range) => {
    store.query.filter.student_count_min = range[0]
    store.query.filter.student_count_max = range[1]
})

watch(teacher_count_range, (range) => {
    store.query.filter.teacher_count_min = range[0]
    store.query.filter.teacher_count_max = range[1]
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

                        <Button data-testid="batches-hide-filter"
                                class="p-button-sm"
                                @click="store.show_advance_filters = false">
                            <i class="pi pi-times"></i>
                        </Button>

                    </div>

                </template>
                

        
            <VhFieldVertical >
                <template #label>
                    <b>Student Count Range:</b>
                </template>

                <div>
                    <div class="mb-3 p-2">
                        <Slider
                            v-model="student_count_range"
                            range
                            :min="0"
                            :max="store.assets.total_students"
                            :step="1"
                            class="w-full"
                        />
                        <div class="text-sm mt-1 text-gray-500">
                            {{ student_count_range[0] }} - {{ student_count_range[1] }}
                        </div>
                    </div>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Teacher Count Range:</b>
                </template>

                <div>
                    <div class="mb-3 p-2">
                        <Slider
                            v-model="teacher_count_range"
                            range
                            :min="0"
                            :max="store.assets.total_teachers"
                            :step="1"
                            class="w-full"
                        />
                        <div class="text-sm mt-1 text-gray-500">
                            {{ teacher_count_range[0] }} - {{ teacher_count_range[1] }}
                        </div>
                    </div>
                </div>

            </VhFieldVertical>

        </Panel>

    </div>
</template>
