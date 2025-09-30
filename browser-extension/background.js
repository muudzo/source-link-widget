// Background script for the Source Link Widget
// This script runs in the background and can handle extension events

// Listen for extension installation
chrome.runtime.onInstalled.addListener((details) => {
    if (details.reason === 'install') {
        console.log('Source Link Widget installed');
        
        // Open the setup page
        chrome.tabs.create({
            url: chrome.runtime.getURL('setup.html')
        });
    }
});

// Listen for messages from content scripts and popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === 'openPopup') {
        // Open the extension popup
        chrome.action.openPopup();
    }
});

// Handle tab updates to potentially show the floating button
chrome.tabs.onUpdated.addListener((tabId, changeInfo, tab) => {
    if (changeInfo.status === 'complete' && tab.url) {
        // You can add logic here to inject the floating button on specific sites
        // For now, we'll leave it commented out
        // chrome.scripting.executeScript({
        //     target: { tabId: tabId },
        //     function: addFloatingButton
        // });
    }
});
