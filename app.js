messaging.onMessage(payload => {
  console.log("Announcement received:", payload);

  const audio = new Audio('/sounds/emergency.mp3');
  audio.play().catch(err => console.log("Audio error:", err));

  const utterance = new SpeechSynthesisUtterance(payload.notification.body);
  utterance.lang = "tl-PH"; // Tagalog kung available
  speechSynthesis.speak(utterance);
});
