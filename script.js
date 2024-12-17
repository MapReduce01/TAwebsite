function toggleFolder(folderName) {
    const contentDiv = document.getElementById(folderName);
    if (contentDiv.style.display === "none" || contentDiv.style.display === "") {
        contentDiv.style.display = "block";
        loadFolderContents(folderName, contentDiv);
    } else {
        contentDiv.style.display = "none";
    }
}

function loadFolderContents(folderName, contentDiv) {
    fetch(`getFolderContents.php?folder=${folderName}`)
        .then(response => response.json())
        .then(data => {
            contentDiv.innerHTML = '';
            data.forEach(file => {
                const link = document.createElement('a');
                link.href = `${folderName}/${file}`;
                link.textContent = file;
                link.target = "_blank";
                contentDiv.appendChild(link);
                contentDiv.appendChild(document.createElement('br'));
            });
        })
        .catch(error => console.error('Error fetching folder contents:', error));
}