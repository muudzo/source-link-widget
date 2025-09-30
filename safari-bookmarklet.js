// Safari Bookmarklet for Source Link Widget
// Save this as a bookmark in Safari and click it on any page to save the link

(function() {
    // Configuration - Update this with your server URL
    const API_BASE_URL = 'http://localhost:8000';
    
    // Get current page data
    const pageData = {
        title: document.title,
        url: window.location.href,
        description: getMetaDescription(),
        favicon: getFaviconUrl()
    };
    
    // Get meta description
    function getMetaDescription() {
        const metaDescription = document.querySelector('meta[name="description"]');
        return metaDescription ? metaDescription.content : '';
    }
    
    // Get favicon URL
    function getFaviconUrl() {
        const favicon = document.querySelector('link[rel="icon"], link[rel="shortcut icon"]');
        if (favicon) {
            return favicon.href;
        }
        return `https://www.google.com/s2/favicons?domain=${window.location.hostname}`;
    }
    
    // Create a simple form dialog
    function createSaveDialog() {
        // Remove existing dialog if any
        const existingDialog = document.getElementById('source-link-dialog');
        if (existingDialog) {
            existingDialog.remove();
        }
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.id = 'source-link-dialog';
        dialog.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        `;
        
        // Create form
        const form = document.createElement('div');
        form.style.cssText = `
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        `;
        
        form.innerHTML = `
            <h2 style="margin: 0 0 20px 0; color: #333; font-size: 24px;">Save Link to Source Widget</h2>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Title:</label>
                <input type="text" id="link-title" value="${pageData.title}" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Description:</label>
                <textarea id="link-description" rows="3" 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;">${pageData.description}</textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Category:</label>
                <select id="link-category" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">Select a category (optional)</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button id="cancel-btn" style="padding: 12px 24px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    Cancel
                </button>
                <button id="save-btn" style="padding: 12px 24px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    Save Link
                </button>
            </div>
            
            <div id="status-message" style="margin-top: 15px; padding: 10px; border-radius: 6px; display: none;"></div>
        `;
        
        dialog.appendChild(form);
        document.body.appendChild(dialog);
        
        // Load categories
        loadCategories();
        
        // Add event listeners
        document.getElementById('cancel-btn').onclick = () => dialog.remove();
        document.getElementById('save-btn').onclick = saveLink;
        
        // Close on background click
        dialog.onclick = (e) => {
            if (e.target === dialog) dialog.remove();
        };
    }
    
    // Load categories from API
    async function loadCategories() {
        try {
            const response = await fetch(`${API_BASE_URL}/api/categories`);
            const categories = await response.json();
            
            const select = document.getElementById('link-category');
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }
    
    // Save link to server
    async function saveLink() {
        const title = document.getElementById('link-title').value.trim();
        const description = document.getElementById('link-description').value.trim();
        const categoryId = document.getElementById('link-category').value;
        
        if (!title) {
            showStatus('Please enter a title', 'error');
            return;
        }
        
        const linkData = {
            title: title,
            url: pageData.url,
            description: description || null,
            favicon: pageData.favicon,
            is_active: true,
            categories: categoryId ? [parseInt(categoryId)] : []
        };
        
        showStatus('Saving link...', 'loading');
        
        try {
            const response = await fetch(`${API_BASE_URL}/api/links`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(linkData)
            });
            
            if (response.ok) {
                showStatus('Link saved successfully!', 'success');
                setTimeout(() => {
                    document.getElementById('source-link-dialog').remove();
                }, 2000);
            } else {
                const errorData = await response.json();
                showStatus(`Error: ${errorData.message || 'Failed to save link'}`, 'error');
            }
        } catch (error) {
            console.error('Error saving link:', error);
            showStatus('Error saving link. Check your connection.', 'error');
        }
    }
    
    // Show status message
    function showStatus(message, type) {
        const statusDiv = document.getElementById('status-message');
        statusDiv.textContent = message;
        statusDiv.style.display = 'block';
        statusDiv.style.background = type === 'success' ? '#d4edda' : 
                                   type === 'error' ? '#f8d7da' : '#d1ecf1';
        statusDiv.style.color = type === 'success' ? '#155724' : 
                               type === 'error' ? '#721c24' : '#0c5460';
    }
    
    // Show the dialog
    createSaveDialog();
})();
