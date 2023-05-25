/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import Like from './scripts/like';
import './styles/app.css';
import 'tw-elements';

document.addEventListener('DOMContentLoaded', () => {
    // Like's system
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if (likeElements) {
        new Like(likeElements);
    }    
})
