//Importerer libaries
const { fireEvent } = require('@testing-library/dom');

//Laver Mock UP af HTML
document.body.innerHTML = `
  <div id="imageContainer"></div>
  <input type="radio" id="Aalborg" name="location">
  <input type="radio" id="Aarhus" name="location">
  <input type="radio" id="Odense" name="location">
  <input type="radio" id="Copenhagen" name="location">
  <button id="resetFormBtn"></button>
`;

//Henter mine funktioner fra public_js/activity.js
const { changeImage, resetImageNothing } = require('../public_js/activity');

describe('changeImage function', () => {
  test('Skal vise Aalborg.png når man trykker på Aalborg radio button', () => {
    const aalborg = document.getElementById('Aalborg');
    const imageContainer = document.getElementById('imageContainer');

    //Simulerer et klik på radio button
    fireEvent.click(aalborg); 
    changeImage();

    expect(imageContainer.innerHTML).toContain('/public_img/byerImages/Aalborg.png');
  });

  test('Skal vise Aarhus.png når man trykker på Aarhus radio button', () => {
    const aarhus = document.getElementById('Aarhus');
    const imageContainer = document.getElementById('imageContainer');

    //Simulerer et klik på radio button
    fireEvent.click(aarhus);
    changeImage();

    expect(imageContainer.innerHTML).toContain('/public_img/byerImages/Aarhus.png');
  });

  test('Skal vise Odense.png når man trykker på Odense radio button', () => {
    const odense = document.getElementById('Odense');
    const imageContainer = document.getElementById('imageContainer');

    //Simulerer et klik på radio button
    fireEvent.click(odense);
    changeImage();

    expect(imageContainer.innerHTML).toContain('/public_img/byerImages/Odense.png');
  });
  
  test('Skal vise Copenhagen.png når man trykker på Copenhagen radio button', () => {
    const copenhagen = document.getElementById('Copenhagen');
    const imageContainer = document.getElementById('imageContainer');

    //Simulerer et klik på radio button
    fireEvent.click(copenhagen);
    changeImage();

    expect(imageContainer.innerHTML).toContain('/public_img/byerImages/Copenhagen.png');
  });
});

describe('resetImageNothing function', () => {
  test('Skal fjerne de marked radio buttons og viser et tomt billede', () => {
    const resetButton = document.getElementById('resetFormBtn');
    const imageContainer = document.getElementById('imageContainer');

    //Sætter en tom billede string i imageContainer for at tjekke at reset virker
    imageContainer.innerHTML = '<img src="some-image.png">';
    fireEvent.click(resetButton);
    resetImageNothing();

    expect(imageContainer.innerHTML).toBe('');
  });
});
