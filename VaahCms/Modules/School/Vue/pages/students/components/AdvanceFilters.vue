<script  setup>

import { useStudentStore } from '../../../stores/store-students'
import VhFieldVertical from './../../../vaahvue/vue-three/primeflex/VhFieldVertical.vue'

const store = useStudentStore();

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

                        <Button data-testid="students-hide-filter"
                                class="p-button-sm"
                                @click="store.show_advance_filters = false">
                            <i class="pi pi-times"></i>
                        </Button>

                    </div>

                </template>

            <VhFieldVertical >
                <template #label>
                    <b>Batch:</b>
                </template>

                <div class="flex gap-1">
                        <MultiSelect 
                            v-model="store.query.filter.batch" 
                            display="chip" 
                            :options="store.assets.batches" 
                            filter optionLabel="name" 
                            optionValue="id" 
                            data-testid="teachers-filters-batch-only"  
                            placeholder="Select a Batch" 
                            class="w-full md:w-14rem" />
                            
                    <Button label="Reset" @click.prevent="store.query.filter.batch = null"/>
                </div>

            </VhFieldVertical>

            <VhFieldVertical >
                <template #label>
                    <b>Genders:</b>
                </template>

                <div class="flex gap-1">
                    <MultiSelect 
                            v-model="store.query.filter.gender" 
                            display="chip" 
                            :options="store.assets.genders" 
                            filter optionLabel="name" 
                            optionValue="id" 
                            data-testid="teachers-filters-gender-only"  
                            placeholder="Select a Gender" 
                            class="w-full md:w-14rem" />
                    <Button label="Reset" @click.prevent="store.query.filter.gender = null"/>
                </div>

            </VhFieldVertical>


             <VhField label="Batches">
                    <div class="p-inputgroup">
                        
                        <div class="required-field hidden"></div>
                    </div>
            </VhField>


        </Panel>

    </div>
</template>
