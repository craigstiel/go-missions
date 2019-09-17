<template>
    <div class="row">
        <div class="col-md-4">
            <v-card flat tile class="first-card">
                <v-container key="New tasks" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">New tasks</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="card in cards" :key="card" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title>I'm a title</v-card-title>
                                <v-card-text>I'm card text</v-card-text>
                                <v-card-actions>
                                    <v-btn text>Show</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="second-card">
                <v-container key="In procces" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">In procces</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="card in cards" :key="card" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title>I'm a title</v-card-title>
                                <v-card-text>I'm card text</v-card-text>
                                <v-card-actions>
                                    <v-btn text>Show</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card flat tile class="third-card">
                <v-container key="Done" fluid>
                    <div class="flex font-weight-light" style="font-size: 24px; font-family: 'Roboto', sans-serif;">Done</div>
                    <v-row>
                        <div class="flex-grow-1"></div>
                        <v-col v-for="card in cards" :key="card" cols="12" sm="6" md="12">
                            <v-card max-width="344" class="mx-auto cards">
                                <v-card-title>I'm a title</v-card-title>
                                <v-card-text>I'm card text</v-card-text>
                                <v-card-actions>
                                    <v-btn text>Show</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            types: ['Places to Be'],
            cards: ['Good', 'Best', 'Finest'],
        }),

        methods: {},
    }
</script>
