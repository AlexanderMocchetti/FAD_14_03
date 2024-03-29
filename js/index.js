const listItems = document.querySelector("ul");
const appendItem = (item, quantity, id_item) => {
    const wrapper = document.createElement('li');
    wrapper.className = "list-group-item d-flex justify-content-between align-items-center";
    wrapper.innerHTML = [
        `<button type="button" class="btn-close" onclick="handleRemoval(${id_item})" aria-label="Close"></button>`,
        item,
        `<span class="badge text-bg-primary rounded-pill">${quantity}</span>`,
    ].join('');
    listItems.appendChild(wrapper);
};

const handleRemoval = id_item => {
    fetch("https://fad.fantagita.site/api/items/remove", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id_item: id_item
        })
    })
    .then(res => {
        clearItems();
        generateItems();    
    })
    .catch(console.error);
};


const clearItems = () => {
    while (listItems.firstChild) {
        listItems.removeChild(listItems.firstChild);
    }
};

const generateAllPossibleItems = () => {
    fetch()
};

const generateItems = () => {
    fetch("https://fad.fantagita.site/api/items")
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            json.items.forEach(item => {
                appendItem(item.name, item.quantity, item.id);
            });
        } else {
            throw new Error(json.message);
        }
    })
    .catch(console.error);
};
document.addEventListener("DOMContentLoaded", generateItems)
