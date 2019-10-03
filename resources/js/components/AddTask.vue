<template>
    <v-form ref="form">
    <v-card width="1000" class="mx-auto mycontent-left" style="margin-top: 30px">
        <v-toolbar color="purple darken-3" dark>
            <v-toolbar-title style="font-size: 16px; font-family: 'Roboto', sans-serif;">ADD TASK</v-toolbar-title>
        </v-toolbar>
        <v-row>
            <v-col cols="12" md="7" key=1>
                <v-text-field style="margin-left: 15px" v-model="title" :rules="titleRules" label="Title" required></v-text-field>
            </v-col>
            <v-col cols="12" md="1" key=2></v-col>
            <v-col cols="12" md="3" key=3 style="margin-top: 20px">
                <v-select :items="dicts.items" label="Priority" v-model="item" outlined></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="7" key=1>
                <v-textarea style="margin-left: 15px; margin-top: -30px" outlined name="input-7-4" label="Description" :rules="descriptionRules" v-model="description"></v-textarea>
            </v-col>
            <v-col cols="12" md="1" key=2></v-col>
            <v-col cols="12" md="3" key=3 style="margin-top: -10px">
                <v-select :items="dicts.types" item-value="id" item-text="name" label="Type" v-model="type" outlined></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="7" key=1>
                <v-image v-model="value" style="margin-top: -20px; margin-left: 15px"></v-image>
            </v-col>
            <v-col cols="12" md="1" key=2></v-col>
            <v-col cols="12" md="3" key=3 style="margin-top: -50px" v-if="multiple">
                <v-select :items="dicts.masters" item-value="id" item-text="name" label="Master" v-model="master" outlined></v-select>
            </v-col>
        </v-row>
        <v-dialog v-model="dialog" persistent max-width="290">
            <template v-slot:activator="{ on }">
                <v-btn @click="add_task()" v-if="lock_btn" v-on="on">Save</v-btn>
            </template>
            <v-card>
                <v-card-title class="headline">Task was added successful.</v-card-title>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="green darken-1" text @click="$router.push({name: 'tasks'})">OK</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
    </v-form>
</template>

<script>
    export default {
        mounted: function () {
            let _this = this;
            axios.get('/dict/masters/get')
                .then(function (response) {
                    _this.dicts.masters = response.data.masters;
                });
            axios.get('/dict/task_types/get')
                .then(function (response) {
                    _this.dicts.types = response.data.task_types;
                });
            axios.get('/dict/multiple/get')
                .then(function (response) {
                    _this.multiple = response.data.multiple;
                });
        },
        data: () => ({
            titleRules: [
                v => !!v || 'Title is required',
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
            ],
            title: '',
            description: '',
            dicts: {
                masters: [],
                items: ['low', 'medium', 'high'],
                types: [],
            },
            type: null,
            item: null,
            master: null,
            dialog: false,
            lock_btn: false,
            multiple: true,
            value: [],
        }),
        watch: {
            title: function() {
                if(this.description !== '') {
                    this.lock_btn = true;
                }
            },
            description: function() {
                if(this.title !== '') {
                    this.lock_btn = true;
                }
            },
        },
        methods: {
            add_task: function () {
                let _this = this;
                if (this.$refs.form.validate()) {
                    let data = {
                        title: _this.title,
                        description: _this.description,
                        type: _this.type,
                        priority: _this.item,
                        master: _this.master,
                        images: _this.value,
                    };
                    axios.post('/task/add', data)
                        .then(function () {
                            _this.dialog = true;
                        });
                }
            },
        },
    }
</script>

<style>
    .mycontent-left {
        border-right: 1px dashed #333;
    }
</style>
