// Tooltips
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});


// Get Subcategories & Sub Subcategories (Cascading AJAX)
$(document).ready(function () {
    const categorySelect = $('#category');
    const subcategorySelect = $('#subcategory');
    const subsubcategorySelect = $('#subsubcategory');

    const selectedSubcategoryId = subcategorySelect.data('selectedsubcategory') || '';
    const selectedSubsubcategoryId = subsubcategorySelect.data('selectedsubsubcategory') || '';

    function setSelectOptions($select, placeholder, items = [], selectedId = '') {
        let options = `<option value="">${placeholder}</option>`;

        if (Array.isArray(items) && items.length > 0) {
            items.forEach(function (item) {
                let isSelected = (selectedId && String(item.id) === String(selectedId)) ? 'selected' : '';
                options += `<option value="${item.id}" ${isSelected}>${item.name}</option>`;
            });
        }

        $select.html(options);
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.trigger('change.select2');
        }
    }

    function loadSubcategories(categoryId, preselectSubId = '', preselectSubSubId = '') {
        // Category change hole sub-subcategory age reset hobe
        setSelectOptions(subsubcategorySelect, 'Select Sub Subcategory', [], '');

        if (!categoryId) {
            setSelectOptions(subcategorySelect, 'Select Sub Category', [], '');
            return;
        }

        setSelectOptions(subcategorySelect, 'Loading...', [], '');

        fetch('/admin/product/get-subcategories/' + encodeURIComponent(categoryId))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch subcategories');
                }
                return response.json();
            })
            .then(data => {
                setSelectOptions(subcategorySelect, 'Select Sub Category', data, preselectSubId);

                if (preselectSubId) {
                    loadSubSubcategories(preselectSubId, preselectSubSubId);
                }
            })
            .catch(error => {
                console.error('Error fetching subcategories:', error);
                setSelectOptions(subcategorySelect, 'Select Sub Category', [], '');
            });
    }

    function loadSubSubcategories(subcategoryId, preselectSubSubId = '') {
        if (!subcategoryId) {
            setSelectOptions(subsubcategorySelect, 'Select Sub Subcategory', [], '');
            return;
        }

        setSelectOptions(subsubcategorySelect, 'Loading...', [], '');

        fetch('/admin/product/get-subsubcategories/' + encodeURIComponent(subcategoryId))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch sub-subcategories');
                }
                return response.json();
            })
            .then(data => {
                // Sub-subcategory na thakle shudhu placeholder thakbe
                setSelectOptions(subsubcategorySelect, 'Select Sub Subcategory', data, preselectSubSubId);
            })
            .catch(error => {
                console.error('Error fetching sub-subcategories:', error);
                setSelectOptions(subsubcategorySelect, 'Select Sub Subcategory', [], '');
            });
    }

    // Category change event
    categorySelect.on('change', function () {
        let categoryId = $(this).val();
        loadSubcategories(categoryId);
    });

    // Subcategory change event
    subcategorySelect.on('change', function () {
        let subcategoryId = $(this).val();
        loadSubSubcategories(subcategoryId);
    });

    // Edit page-e age theke category & subcategory thakle auto load
    if (categorySelect.val()) {
        loadSubcategories(categorySelect.val(), selectedSubcategoryId, selectedSubsubcategoryId);
    }
});


$(document).ready(function () {

    // ১. ভ্যালু লোড করার কমন ফাংশন
    function loadAttributeValues(attributeId, targetDropdown, selectedValue = null) {
        if (!attributeId) {
            targetDropdown.html('<option value="">Select Attribute Value</option>');
            return;
        }

        fetch('/admin/product/get-attributeValue/' + attributeId)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select Attribute Value</option>';

                // selectedValue কে স্ট্রিং বা নাম্বারে কনভার্ট করে নিশ্চিত হওয়া
                let checkValue = selectedValue ? selectedValue.toString() : null;

                data.forEach(function (item) {
                    // এখানে চেক করা হচ্ছে ডাটার ID এবং আমাদের ডাটাবেসের ID এক কি না
                    let isSelected = (checkValue && checkValue == item.id.toString()) ? 'selected' : '';
                    options += `<option value="${item.id}" ${isSelected}>${item.value}</option>`;
                });

                targetDropdown.html(options);
            })
            .catch(error => console.error('Error fetching attribute values:', error));
    }

    // ২. পেজ লোড হওয়ার সময় Previous Variation গুলোর ভ্যালু লোড করা
    $('.variationRow').each(function () {
        let attributeId = $(this).find('.attribute').val();
        let targetDropdown = $(this).find('.attributeValue');
        let selectedValue = targetDropdown.data('selected'); // data-selected থেকে আইডি নিবে

        if (attributeId) {
            loadAttributeValues(attributeId, targetDropdown, selectedValue);
        }
    });

    // ৩. অ্যাট্রিবিউট চেঞ্জ করলে নতুন ভ্যালু লোড করা
    $(document).on('change', '.attribute', function () {
        let attributeId = $(this).val();
        let targetDropdown = $(this).closest('.variationRow').find('.attributeValue');
        loadAttributeValues(attributeId, targetDropdown);
    });

});


// Generate SKU
document.addEventListener("DOMContentLoaded", function () {

    function generateNumericSKU(length = 13) {
        // Only digits 0-9
        let code = '';
        const digits = '0123456789';
        for (let i = 0; i < length; i++) {
            code += digits.charAt(Math.floor(Math.random() * digits.length));
        }
        return code;
    }

    const btn = document.getElementById('generate_code');
    const skuInput = document.getElementById('barcodeInput');

    btn.addEventListener('click', function () {
        const code = generateNumericSKU(13);
        skuInput.value = code;
    });

});


// Variation show/hide
document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById("status");
    const variationBody = document.getElementById("variationBody");
    const baseValue = document.getElementById("baseValue");

    toggle.addEventListener("change", function () {

        if (this.checked) {
            variationBody.style.display = "block";
            baseValue.style.display = "none";
        } else {
            variationBody.style.display = "none";
            baseValue.style.display = "block";
        }
    });
});

// Variation



// Thumbnail Image Upload
document.addEventListener("DOMContentLoaded", function () {
    const thumbnailBox = document.getElementById("thumbnailBox");
    const thumbnailInput = document.getElementById("thumbnailInput");
    const thumbnailPreview = document.getElementById("thumbnailPreview");
    const thumbnailText = document.getElementById("thumbnailText");

    thumbnailBox.onclick = () => thumbnailInput.click();
    thumbnailInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                thumbnailPreview.src = e.target.result;
                thumbnailPreview.style.display = "block";
                thumbnailText.style.display = "none";
            };
            reader.readAsDataURL(file);
        }
    });
});


// Multiple Image Upload (নতুন ইমেজের জন্য)
document.addEventListener("DOMContentLoaded", function () {

    const container = document.getElementById("imagePreviewContainer");

    function createUploadBox() {

        const box = document.createElement("div");
        box.classList.add("image-box");
        box.style.width = "200px";
        box.style.height = "200px";
        box.style.border = "2px dashed #ccc";
        box.style.borderRadius = "8px";
        box.style.position = "relative";
        box.style.cursor = "pointer";
        box.style.display = "flex";
        box.style.alignItems = "center";
        box.style.justifyContent = "center";
        box.style.background = "#fff";

        const input = document.createElement("input");
        input.type = "file";
        input.name = "gallery[]"; // নতুন ইমেজ ফর্মে পাঠানোর জন্য নাম
        input.accept = "image/png,image/jpeg";
        input.hidden = true;

        const text = document.createElement("span");
        text.innerHTML = "Upload Image";

        box.appendChild(text);
        box.appendChild(input);

        box.onclick = () => input.click();

        input.addEventListener("change", function () {

            if (!this.files || this.files.length === 0) {
                return;
            }

            const file = this.files[0];

            const reader = new FileReader();

            reader.onload = function (e) {

                box.innerHTML = `
                    <img src="${e.target.result}"
                    style="width:100%;height:100%;object-fit:cover;border-radius:6px;">

                    <button type="button" class="remove-new-img"
                    style="position:absolute;top:5px;right:5px;background:red;color:white;border:none;border-radius:50%;width:25px;height:25px;">
                    ×
                    </button>
                `;

                // ইনপুট ফাইলটি বক্সে যোগ করা যাতে ফর্মে ডেটা যায়
                box.appendChild(input);

                box.querySelector(".remove-new-img").onclick = function (ev) {
                    ev.stopPropagation();
                    box.remove();
                };

                createUploadBox(); // নতুন আপলোড বক্স যোগ করা
            };

            reader.readAsDataURL(file);
        });

        // কন্টেইনারের শেষে যোগ করা
        container.appendChild(box);
    }

    // প্রথমবার আপলোড বক্স তৈরি করা
    createUploadBox();

});


// Meta Image Upload
document.addEventListener("DOMContentLoaded", function () {
    const metaImgBox = document.getElementById("metaImgBox");
    const metaImgInput = document.getElementById("metaImgInput");
    const metaImgPreview = document.getElementById("metaImgPreview");
    const metaImgText = document.getElementById("metaImgText");

    metaImgBox.onclick = () => metaImgInput.click();
    metaImgInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                metaImgPreview.src = e.target.result;
                metaImgPreview.style.display = "block";
                metaImgText.style.display = "none";
            };
            reader.readAsDataURL(file);
        }
    });
});


// Meta Title Sync with Name
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const metaTitle = document.querySelector("input[name='meta_title']");
    nameInput.addEventListener("input", function () {
        metaTitle.value = this.value;
    });
});


// Reset Form Button
document.getElementById("resetFormBtn").addEventListener("click", function () {
    document.getElementById("productForm").reset();
});
