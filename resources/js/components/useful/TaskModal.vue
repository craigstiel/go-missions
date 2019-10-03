<template>
    <v-card>
    <v-card-text>
        <v-container>
            <v-row>
                <v-col cols="12" md="12"><v-card-title>{{task.title}}</v-card-title></v-col>
                <v-col cols="12" md="10" style="margin-top: -30px"><v-card-text>{{task.description}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -30px"><v-card-text>{{task.priority}}</v-card-text></v-col>
                <v-col cols="12" md="12" style="margin-top: -30px" v-if="task.images !== null">
                    <v-card>
                        <v-card-text>
                            <v-container v-for="image in task.images" :key="image.id" >
                                <v-prew-image class="image" :src="image.code"></v-prew-image>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>Type:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px"><v-card-text :style="{'color': task.type_color}">{{task.type}}</v-card-text></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px"><v-card-text>Master:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-if="task.user_position !== 'admin'"><v-card-text>{{task.master}}</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -20px" v-else><v-select :items="dicts.masters" item-value="id" item-text="name" label="Master" v-model="task.master"></v-select></v-col>
                <v-col cols="12" md="2" style="margin-top: -50px"><v-card-text>Date:</v-card-text></v-col>
                <v-col cols="12" md="3" style="margin-top: -50px"><v-card-text>{{task.created_at}}</v-card-text></v-col>
                <v-col cols="12" md="7" style="margin-top: -50px"></v-col>
                <v-col cols="12" md="2" style="margin-top: -20px; margin-left: 15px">Client:</v-col>
                <v-col cols="12" md="5" style="margin-top: -20px">
                    <v-card>
                        <v-card-text>
                            <v-container>
                                <v-card-title style="font-size: 16px">{{task.client}}</v-card-title>
                                <v-card-text style="margin-top: -20px">{{task.client_phone}}</v-card-text>
                                <v-card-text style="margin-top: -20px">{{task.client_email}}</v-card-text>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-card-text>
    <v-card-actions>
        <div class="flex-grow-1"></div>
        <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
    </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        props: ['task'],
        mounted: function () {
            let _this = this;
            axios.get('/dict/masters/get')
                .then(function (response) {
                    _this.dicts.masters = response.data.masters;
                });
        },
        data: () => ({
            dicts: {
                masters: [],
            },
        }),
    }
</script>

<style scoped>

</style>
