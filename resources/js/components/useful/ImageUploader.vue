<template>
    <div class="uploader"
         @dragenter="OnDragEnter"
         @dragleave="OnDragLeave"
         @dragover.prevent
         @drop="onDrop"
         :class="{ dragging: isDragging }">

        <div class="upload-control" v-show="images.length">
            <label for="file">{{ $ml.with('VueJS').get('select_file') }}</label>
            <button @click="upload">{{ $ml.with('VueJS').get('upload') }}</button>
        </div>

        <div v-show="!images.length">
            <p style="color: #AB75CC">{{ $ml.with('VueJS').get('drag') }}</p>
            <div style="color: #AB75CC">{{ $ml.with('VueJS').get('or') }}</div>
            <div class="file-input">
                <label for="file">{{ $ml.with('VueJS').get('select_file') }}</label>
                <input type="file" id="file" @change="onInputChange" multiple>
            </div>
        </div>

        <div class="images-preview" v-show="images.length">
            <div class="img-wrapper" v-for="(image, index) in images" :key="index">
                <img v-model="images" :src="image" :alt="`Image Uplaoder ${index}`">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        mounted: function () {
            this.images = this.value;
        },
        data: () => ({
            isDragging: false,
            dragCount: 0,
            files: [],
            images: []
        }),
        watch: {
            value: function () {
                this.images = this.value;
            },
            images: function () {
                this.$emit("img", this.images);
            },
        },
        methods: {
            OnDragEnter(e) {
                e.preventDefault();

                this.dragCount++;
                this.isDragging = true;

                return false;
            },
            OnDragLeave(e) {
                e.preventDefault();
                this.dragCount--;

                if (this.dragCount <= 0)
                    this.isDragging = false;
            },
            onInputChange(e) {
                const files = e.target.files;

                Array.from(files).forEach(file => this.addImage(file));
            },
            onDrop(e) {
                e.preventDefault();
                e.stopPropagation();

                this.isDragging = false;

                const files = e.dataTransfer.files;

                Array.from(files).forEach(file => this.addImage(file));
            },
            addImage(file) {
                if (!file.type.match('image.*')) {
                    this.$toastr.e(`${file.name} is not an image`);
                    return;
                }

                this.files.push(file);

                const img = new Image(),
                    reader = new FileReader();

                reader.onload = (e) => this.images.push(e.target.result);

                reader.readAsDataURL(file);
            },
            getFileSize(size) {
                const fSExt = ['Bytes', 'KB', 'MB', 'GB'];
                let i = 0;

                while(size > 900) {
                    size /= 1024;
                    i++;
                }

                return `${(Math.round(size * 100) / 100)} ${fSExt[i]}`;
            },
            upload() {
                const formData = new FormData();

                this.files.forEach(file => {
                    formData.append('images[]', file, file.name);
                });

                axios.post('/images-upload', formData)
                    .then(response => {
                        this.$toastr.s('All images uplaoded successfully');
                        this.images = [];
                        this.files = [];
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .uploader {
        width: 100%;
        background: #55107f;
        padding: 15px 15px;
        text-align: center;
        border-radius: 10px;
        border: 1px solid #8e4fb5;
        font-size: 16px;
        position: relative;

        &.dragging {
            background: #AB75CC;
            color: #8240aa;
            border: 1px solid #8e4fb5;

            .file-input label {
                background: #8240aa;
                color: #55107f;
            }
        }

        i {
            font-size: 85px;
        }

        .file-input {
            width: 200px;
            margin: auto;
            height: 68px;
            position: relative;

            label,
            input {
                background: #6a1b9a;
                color: #AB75CC;
                width: 100%;
                position: absolute;
                left: 0;
                top: 0;
                padding: 10px;
                border-radius: 4px;
                margin-top: 7px;
                cursor: pointer;
            }

            input {
                opacity: 0;
                z-index: -2;
            }
        }

        .images-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;

            .img-wrapper {
                width: 160px;
                display: flex;
                flex-direction: column;
                margin: 10px;
                height: 150px;
                justify-content: space-between;
                background: #fff;
                box-shadow: 5px 5px 20px #3e3737;

                img {
                    max-height: 150px;
                }
            }
        }

        .upload-control {
            position: absolute;
            width: 100%;
            background: #6a1b9a;
            top: 0;
            left: 0;
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
            padding: 10px;
            padding-bottom: 4px;
            text-align: right;

            button, label {
                background: #8240aa;
                border: 2px solid #8240aa;
                color: #fff;
                font-size: 15px;
                cursor: pointer;
            }

            label {
                padding: 2px 5px;
                margin-right: 10px;
            }
        }
    }
</style>
