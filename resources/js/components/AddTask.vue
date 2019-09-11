<template>
    <v-card width="1000" class="mx-auto mycontent-left" style="margin-top: 30px">
        <v-row>
            <v-col cols="12" md="7" key=1>
                <v-text-field style="margin-left: 15px" v-model="title" :rules="titleRules" label="Title" required></v-text-field>
            </v-col>
            <v-col cols="12" md="1" key=2></v-col>
            <v-col cols="12" md="3" key=3>
                <v-select :items="items" label="Priority" v-model="item" outlined></v-select>
            </v-col>
        </v-row>
        <v-col cols="12" md="7">
            <v-textarea outlined name="input-7-4" label="Description" :rules="descriptionRules" v-model="description"></v-textarea>
        </v-col>
        <v-col cols="12" md="7">
            <v-image v-model="value" style="margin-top: -20px"></v-image>
        </v-col>
        <v-card-actions>
            <v-btn text>Save</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        data: () => ({
            titleRules: [
                v => !!v || 'Title is required',
                v => (v && v.length > 255) || 'Name must be less than 255 characters',
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
            ],
            title: '',
            description: '',
            items: ['low', 'medium', 'high'],
            item: null,
            value: [],
        }),
        methods: {},
    }
</script>

<style>
    .mycontent-left {
        border-right: 1px dashed #333;
    }
</style>
