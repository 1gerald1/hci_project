let decks = [];

function createDeck() {
  const deckName = document.getElementById('deckName').value.trim();

  if (deckName === "") {
    alert("Please enter a deck name.");
    return;
  }

  const deck = {
    name: deckName,
    flashcards: []
  };

  decks.push(deck);
  updateDeckList();
  document.getElementById('deckName').value = "";
}

function updateDeckList() {
  const deckListElement = document.getElementById('deckList');
  deckListElement.innerHTML = ''; // Clear the list

  decks.forEach((deck, index) => {
    const deckItem = document.createElement('li');
    deckItem.classList.add('deck-item');
    deckItem.innerHTML = `
      ${deck.name} 
      <button onclick="viewDeck(${index})">View</button>
      <button onclick="deleteDeck(${index})">Delete</button>
    `;
    deckListElement.appendChild(deckItem);
  });
}

function viewDeck(index) {
  const deck = decks[index];
  document.getElementById('deckTitle').innerText = deck.name;

  const deckView = document.getElementById('deckView');
  deckView.style.display = 'block';
  document.getElementById('deckList').style.display = 'none';

  updateFlashcards(deck);
}

function addFlashcard() {
  const question = document.getElementById('question').value.trim();
  const answer = document.getElementById('answer').value.trim();

  if (question === "" || answer === "") {
    alert("Please enter both question and answer.");
    return;
  }

  const flashcard = { question, answer };
  const deckIndex = decks.findIndex(deck => deck.name === document.getElementById('deckTitle').innerText);
  decks[deckIndex].flashcards.push(flashcard);

  updateFlashcards(deckIndex);

  document.getElementById('question').value = "";
  document.getElementById('answer').value = "";
}

function updateFlashcards(deckIndex) {
  const flashcardListElement = document.getElementById('flashcardList');
  flashcardListElement.innerHTML = ''; // Clear the list

  const deck = decks[deckIndex];
  
  // Shuffle flashcards
  shuffle(deck.flashcards);

  deck.flashcards.forEach((flashcard, index) => {
    const flashcardItem = document.createElement('div');
    flashcardItem.classList.add('flashcard-item');
    flashcardItem.innerHTML = `
      <div class="flashcard" onclick="flipCard(event)">
        <div class="front">${flashcard.question}</div>
        <div class="back">${flashcard.answer}</div>
      </div>
      <div class="flashcard-buttons">
        <button class="edit" onclick="editFlashcard(event, ${deckIndex}, ${index})">Edit</button>
        <button class="delete" onclick="deleteFlashcard(${deckIndex}, ${index})">Delete</button>
      </div>
      <input type="text" id="answerInput${index}" placeholder="Type your answer" />
      <button onclick="checkAnswer(${deckIndex}, ${index})">Check Answer</button>
      <div id="feedback${index}" class="feedback"></div>
    `;
    flashcardListElement.appendChild(flashcardItem);
  });
}

function flipCard(event) {
  const flashcard = event.currentTarget; // Get the clicked flashcard
  flashcard.classList.toggle('flipped'); // Toggle the 'flipped' class to rotate the card
}

function checkAnswer(deckIndex, flashcardIndex) {
  const userAnswer = document.getElementById(`answerInput${flashcardIndex}`).value.trim();
  const correctAnswer = decks[deckIndex].flashcards[flashcardIndex].answer;
  const feedbackElement = document.getElementById(`feedback${flashcardIndex}`);

  if (userAnswer === correctAnswer) {
    feedbackElement.innerHTML = "Correct!";
    feedbackElement.classList.remove('incorrect');
    feedbackElement.classList.add('correct');
  } else {
    feedbackElement.innerHTML = "Incorrect, try again!";
    feedbackElement.classList.remove('correct');
    feedbackElement.classList.add('incorrect');
  }
}

function editFlashcard(event, deckIndex, flashcardIndex) {
  event.stopPropagation(); // Prevent the card from flipping when editing
  const flashcard = decks[deckIndex].flashcards[flashcardIndex];

  const question = prompt("Edit question:", flashcard.question);
  const answer = prompt("Edit answer:", flashcard.answer);

  if (question && answer) {
    flashcard.question = question;
    flashcard.answer = answer;
    updateFlashcards(deckIndex);
  }
}

function deleteFlashcard(deckIndex, flashcardIndex) {
  decks[deckIndex].flashcards.splice(flashcardIndex, 1);
  updateFlashcards(deckIndex);
}

function deleteDeck(index) {
  decks.splice(index, 1);
  updateDeckList();
}

function backToDecks() {
  document.getElementById('deckView').style.display = 'none';
  document.getElementById('deckList').style.display = 'block';
}

// Shuffle function to randomize the flashcards
function shuffle(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]]; // Swap elements
  }
}
