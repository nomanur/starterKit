import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export default function imageCropperPlugin(Alpine) {
    Alpine.data('imageCropper', (config = {}) => ({
        cropping: config.cropping ?? false,
        imageUrl: null,
        cropper: null,
        
        init() {
            // Watch for the cropping state to toggle the cropper UI
            this.$watch('cropping', value => {
                if (value && this.imageUrl) {
                    this.initCropper();
                } else {
                    this.destroyCropper();
                }
            });
            
            // Clean up when the component is destroyed
            this.$cleanup(() => {
                this.destroyCropper();
            });
        },
        
        // Call this on `<input type="file" @change="onFileChange">`
        onFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Always reset cropping to true when a new file is selected
            this.cropping = true;
            
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.imageUrl = e.target.result;
                // If cropping is true by default, initialize immediately after image loads
                if (this.cropping) {
                    this.initCropper();
                }
            };
        },
        
        initCropper() {
            this.$nextTick(() => {
                if (this.cropper) this.destroyCropper();
                
                // We expect an image element with x-ref="cropperImage"
                const imageEl = this.$refs.cropperImage;
                if (!imageEl) {
                    console.warn('Image element with x-ref="cropperImage" not found.');
                    return;
                }
                
                this.cropper = new Cropper(imageEl, {
                    aspectRatio: config.aspectRatio ?? NaN,
                    viewMode: 1,
                    autoCropArea: 1,
                });
            });
        },
        
        destroyCropper() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },
        
        // Call this when the user clicks a "Save" or "Crop" button
        saveCrop() {
            if (!this.cropper) return;
            
            const canvas = this.cropper.getCroppedCanvas();
            this.imageUrl = canvas.toDataURL('image/jpeg');
            
            // Generate a Blob/File to be sent to the server
            canvas.toBlob((blob) => {
                const file = new File([blob], 'cropped-image.jpg', { type: 'image/jpeg' });
                
                // Dispatch event so you can catch it outside if needed
                // e.g., @image-cropped="handleCroppedFile($event.detail.file)"
                this.$dispatch('image-cropped', { file, dataUrl: this.imageUrl });
            });
            
            this.destroyCropper();
            this.cropping = false; // Turn off cropping mode
            this.clearInput();
        },
        
        // Call this when the user cancels the crop action
        cancelCrop() {
            this.destroyCropper();
            this.cropping = false;
            this.clearInput();
        },

        // Helper to reset the file input so the same file can be selected again
        clearInput() {
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        }
    }));
}
