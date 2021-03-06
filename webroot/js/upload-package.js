/**
 * ...
 */

const fileInput = document.getElementById('file-upload');
const filename = document.getElementById('filename');
const fileSize = document.getElementById('fileSize');

const initialUploadArea = document.getElementById('initialUploadArea');
const previewArea = document.getElementById('previewArea');

const keyCheckbox = document.getElementById('useKey');
const keySection = document.getElementById('keySection');

const selectFile = (event) => {
  if (event) {
    event.preventDefault();
  }

  fileInput.click();
}

const changeFile = () => {
  const file = fileInput.files[0];

  if (!file) {
    return;
  }

  setFileName(file.name);
  setFileSize(file.size);

  showPreview();
}

const removeFile = () => {
  fileInput.files = (new DataTransfer()).files;
  hidePreview();
}

const showPreview = () => {
  initialUploadArea.classList.add('hidden');
  previewArea.classList.remove('hidden');
}

const hidePreview = () => {
  initialUploadArea.classList.remove('hidden');
  previewArea.classList.add('hidden');
}

const setFileName = (name) => {
  filename.innerText = name;
}

const setFileSize = (size) => {
  fileSize.innerText = filesize(size, { round: 1 });
}

const toggleKeySection = () => {
  if (keyCheckbox.checked) {
    keySection.classList.remove('hidden');

    return;
  }

  keySection.classList.add('hidden');
}
