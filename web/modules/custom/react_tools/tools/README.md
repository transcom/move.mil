# Initial Steps
    - $ npm install

# React App Tools Developer Rules:

    - All micro apps go under the /apps dir
    - You can do an npx create-react-app from that /apps root directory to start a new app.
    - The folder directly under /apps will be the Root dir OF THIS SPECIFIC APP
    - You can create a .vscode (if you are using vscode) launch configuration
    inside of each Apps root for debugging 
    purposes.
    - All apps must have a localcss dir under apps/<app-name>/src/main.css 
    where the sass will compile to. (* note the 
    sass must be compiled before running a build).

# Run Specific App Locally Outside of Drupal (debug mode)

    1. $ npm start
    2. You will be prompted to choose the app name.
    3. A localhost:3000 port will be allocated to serve your app outside of the drupal env.

# Run Specific App Locally Inside of Drupal / Build for Production

    * pre-build requirements
        all apps under the /app dir must have their sass compiled.
        all apps must have an index.js and index.html file

    1. $ npm run build
