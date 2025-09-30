# Safari Setup for Source Link Widget

Since Safari doesn't support Chrome-style extensions, here are your options:

## Option 1: Bookmarklet (Easiest)

### Setup Steps:

1. **Copy the bookmarklet code:**
   - Open the file `safari-bookmarklet.js`
   - Copy the entire code

2. **Create a bookmark in Safari:**
   - Go to any webpage
   - Press `Cmd + D` to create a bookmark
   - In the bookmark dialog, change the name to "Save to Source Widget"
   - In the address field, paste the entire bookmarklet code
   - Save the bookmark

3. **Use the bookmarklet:**
   - Visit any webpage you want to save
   - Click your "Save to Source Widget" bookmark
   - A dialog will appear with the page details
   - Fill in any additional info and click "Save Link"

### How it works:
- The bookmarklet creates a popup dialog on any page
- It automatically captures the page title, URL, and description
- You can add a custom description and select a category
- It saves the link to your Laravel backend

## Option 2: Safari Web Extension (Advanced)

If you want a proper Safari extension, you'll need:

1. **Xcode and macOS development environment**
2. **Convert the Chrome extension to Safari format**

### Steps:
1. Open Xcode
2. Create a new Safari Web Extension project
3. Copy the extension files from `browser-extension/` folder
4. Adapt the code for Safari's API differences
5. Build and install the extension

## Option 3: Use Chrome/Edge for the Extension

The easiest solution is to use Chrome or Edge for the browser extension, and Safari for regular browsing. You can:

1. Install the extension in Chrome/Edge
2. Use Safari for regular browsing
3. When you want to save a link, copy the URL and paste it into Chrome/Edge to use the extension

## Recommendation

I recommend using the **bookmarklet approach** for Safari because:
- ✅ Works immediately without development setup
- ✅ No need for Xcode or complex configuration
- ✅ Same functionality as the browser extension
- ✅ Works on any webpage
- ✅ Easy to set up and use

The bookmarklet provides the same core functionality as the browser extension - you can save links with descriptions and categories directly from any webpage in Safari.
