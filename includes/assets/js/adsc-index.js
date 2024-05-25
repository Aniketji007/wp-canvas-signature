jQuery(document).ready(() => {
    class CanvasDrawer {
        constructor(id) {
            this.wrapper = jQuery(`#${id}`);
            this.canvas = this.wrapper.find('canvas')[0];
            this.context = this.canvas.getContext('2d');
            this.updateBtn = this.wrapper.find('button.update')[0];
            this.clearBtn = this.wrapper.find('button.clear')[0];
            this.isDrawing = false;
            this.lastX = 0;
            this.lastY = 0;
            this.addEventListeners();
        }

        addEventListeners() {
            this.canvas.addEventListener('mousedown', (e) => this.startDrawing(e));
            this.canvas.addEventListener('mousemove', (e) => this.draw(e));
            this.canvas.addEventListener('mouseup', () => this.stopDrawing());
            this.canvas.addEventListener('mouseout', () => this.stopDrawing());
            this.updateBtn.addEventListener('click', () => this.updateData());
            this.clearBtn.addEventListener('click', () => this.clearData());
        }

        startDrawing(e) {
            this.isDrawing = true;
            this.lastX = e.offsetX;
            this.lastY = e.offsetY;
        }

        draw(e) {
            if (!this.isDrawing) return;
            this.context.beginPath();
            this.context.moveTo(this.lastX, this.lastY);
            this.context.lineTo(e.offsetX, e.offsetY);
            this.context.stroke();
            this.lastX = e.offsetX;
            this.lastY = e.offsetY;
        }

        stopDrawing() {
            this.isDrawing = false;
        }

        updateData() {
            if (this.context.getImageData(0, 0, this.canvas.width, this.canvas.height).data.some(channel => channel !== 0)) {
                const canvasUrl = this.canvas.toDataURL('image/png');

                const ajaxData = {
                    signature_image: canvasUrl, // Send the image data as 'signature_image'
                    action: 'signature_data_save', // This matches the registered action in your WordPress AJAX handler
                    nonce: adcsExtraData.nonce // Pass the nonce for security verification
                };

                jQuery.ajax({
                    url: adcsExtraData.ajax_url, // This URL points to admin-ajax.php
                    type: 'POST', // Use POST method
                    data: ajaxData,
                    success: (response) => {
                        console.log('Success:', response);
                    },
                    error: (xhr, status, error) => {
                        console.error('Error:', xhr.responseText);
                    }
                });
            } else {
                alert('Please provide a signature before saving.');
            }
        }

        clearData() {
            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
        }
    }

    jQuery('.adsc_signature_wrapper').each((_, e) => {
        new CanvasDrawer(e.id);
    });
});