// // script.js (updated for full conversation memory)

// const chatbotToggler = document.querySelector(".chatbot-toggler");
// const closeBtn = document.querySelector(".close-btn");
// const chatbox = document.querySelector(".chatbox");
// const chatInput = document.querySelector(".chat-input textarea");
// const sendChatBtn = document.querySelector(".chat-input span");

// let userMessage = null; // Variable to store user's message
// const inputInitHeight = chatInput.scrollHeight;

// const API_URL = document
//   .querySelector('meta[name="api-url"]')
//   .getAttribute("content");

// const conversation = []; // Stores the entire chat history

// const createChatLi = (message, className) => {
//   const chatLi = document.createElement("li");
//   chatLi.classList.add("chat", `${className}`);
//   let chatContent =
//     className === "outgoing"
//       ? `<p></p>`
//       : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
//   chatLi.innerHTML = chatContent;
//   chatLi.querySelector("p").textContent = message;
//   return chatLi;
// };

// const generateResponse = async (chatElement) => {
//   const messageElement = chatElement.querySelector("p");

//   const requestOptions = {
//     method: "POST",
//     headers: { "Content-Type": "application/json" },
//     body: JSON.stringify({
//       contents: conversation,
//     }),
//   };

//   try {

//     const response = await fetch(API_URL, requestOptions); // hna ndiro request lel API ta3 GEMINI

//     const data = await response.json();

//     if (!response.ok || !data.candidates || !data.candidates[0]) {
//       throw new Error(
//         data.error?.message || "No valid response from Gemini API"
//       );
//     }

//     const aiReply = data.candidates[0].content.parts[0].text;
//     messageElement.textContent = aiReply;

//     // Save assistant reply
//     conversation.push({
//       role: "model",
//       parts: [{ text: aiReply }],
//     });
//   } catch (error) {
//     messageElement.classList.add("error");
//     messageElement.textContent = error.message;
//   } finally {
//     chatbox.scrollTo(0, chatbox.scrollHeight);
//   }
// };

// const handleChat = () => {
//   userMessage = chatInput.value.trim();
//   if (!userMessage) return;

//   chatInput.value = "";
//   chatInput.style.height = `${inputInitHeight}px`;

//   chatbox.appendChild(createChatLi(userMessage, "outgoing"));
//   chatbox.scrollTo(0, chatbox.scrollHeight);

//   // Save user message
//   conversation.push({
//     role: "user",
//     parts: [{ text: userMessage }],
//   });

//   setTimeout(() => {
//     const incomingChatLi = createChatLi("Thinking...", "incoming");
//     chatbox.appendChild(incomingChatLi);
//     chatbox.scrollTo(0, chatbox.scrollHeight);
//     generateResponse(incomingChatLi);
//   }, 600);
// };

// chatInput.addEventListener("input", () => {
//   chatInput.style.height = `${inputInitHeight}px`;
//   chatInput.style.height = `${chatInput.scrollHeight}px`;
// });

// chatInput.addEventListener("keydown", (e) => {
//   if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
//     e.preventDefault();
//     handleChat();
//   }
// });

// sendChatBtn.addEventListener("click", handleChat);
// closeBtn.addEventListener("click", () =>
//   document.body.classList.remove("show-chatbot")
// );
// chatbotToggler.addEventListener("click", () =>
//   document.body.classList.toggle("show-chatbot")
// );

// script.js (updated for page content understanding and conversation memory)

const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");

let userMessage = null; // Variable to store user's message
const inputInitHeight = chatInput.scrollHeight;

const API_URL = document
  .querySelector('meta[name="api-url"]')
  .getAttribute("content");

let conversation = []; // Stores the entire chat history, will include page context
let pageContentCache = ""; // Cache for extracted page content

// Function to extract relevant text content from the current webpage
const getPageContentForChatbot = () => {
  if (pageContentCache) {
    return pageContentCache; // Use cached content if available
  }

  let relevantContent = "";
  // Try to get content from more specific elements first
  const mainElement = document.querySelector("main");
  const articleElement = document.querySelector("article");

  if (mainElement) {
    relevantContent = mainElement.innerText;
  } else if (articleElement) {
    relevantContent = articleElement.innerText;
  } else {
    // Fallback: Clone body, remove script, style, nav, footer, header, aside then get innerText
    const bodyClone = document.body.cloneNode(true);
    bodyClone
      .querySelectorAll("script, style, nav, footer, header, aside")
      .forEach((el) => el.remove());
    relevantContent = bodyClone.innerText;
  }

  // Basic cleaning: replace multiple spaces/newlines with a single space
  relevantContent = relevantContent.replace(/\s\s+/g, " ").trim();

  // Truncate if too long to avoid exceeding API limits (adjust MAX_CHARS as needed)
  // Gemini models (like 1.5 Flash) have large context windows, but be mindful of overall request size.
  const MAX_CHARS = 15000; // Roughly 3000-5000 tokens.
  if (relevantContent.length > MAX_CHARS) {
    relevantContent =
      relevantContent.substring(0, MAX_CHARS) +
      "... [Content truncated due to length]";
  }
  pageContentCache = relevantContent; // Cache the extracted content
  return relevantContent;
};

// Function to initialize or prepend page context to the conversation
const ensurePageContextInConversation = () => {
  // Check if context is already the first message (and is a context-setting message)
  if (conversation.length > 0 && conversation[0].isContextSetter) {
    return; // Context already set
  }

  const currentPageContent = getPageContentForChatbot();
  if (currentPageContent) {
    const contextMessage = {
      role: "user", // Using "user" role for broad compatibility
      parts: [
        {
          text: `SYSTEM PROMPT: You are a helpful assistant. The user is currently viewing a webpage with the following content. Please use this information to answer their questions if relevant. When you use this information, you don't need to explicitly state that you're using the page content unless it's natural to do so (e.g., "On this page, it says..."). Focus on answering the question directly. Webpage content: "${currentPageContent}"`,
        },
      ],
      isContextSetter: true, // Custom flag to identify this message
    };
    // Prepend this context to the conversation history
    conversation.unshift(contextMessage);

    // Optional: If you want the bot to acknowledge context, you can add a model response.
    // This would typically require an API call or be a canned response. For simplicity,
    // we'll let the context be "silently" understood by the model.
  }
};

const createChatLi = (message, className) => {
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", `${className}`);
  let chatContent =
    className === "outgoing"
      ? `<p></p>`
      : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi;
};

const generateResponse = async (chatElement) => {
  const messageElement = chatElement.querySelector("p");

  // Ensure page context is in the conversation before sending to API
  ensurePageContextInConversation();

  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      contents: conversation.map((turn) => ({
        role: turn.role,
        parts: turn.parts,
      })), // Strip custom flags like isContextSetter
    }),
  };

  try {
    const response = await fetch(API_URL, requestOptions);
    const data = await response.json();

    if (
      !response.ok ||
      !data.candidates ||
      !data.candidates[0] ||
      !data.candidates[0].content
    ) {
      let errorMessage = "Error from API.";
      if (data.error && data.error.message) {
        // Gemini specific error
        errorMessage = `API Error: ${data.error.message}`;
      } else if (
        data.candidates &&
        data.candidates[0] &&
        data.candidates[0].finishReason &&
        data.candidates[0].finishReason !== "STOP"
      ) {
        errorMessage = `Model generation stopped: ${data.candidates[0].finishReason}.`;
        if (data.candidates[0].safetyRatings) {
          errorMessage += ` Safety: ${data.candidates[0].safetyRatings
            .map(
              (r) =>
                `${r.category.replace("HARM_CATEGORY_", "")}: ${r.probability}`
            )
            .join(", ")}`;
        }
      } else if (
        !data.candidates ||
        !data.candidates[0] ||
        !data.candidates[0].content
      ) {
        errorMessage =
          "No valid response content from Gemini. The request might have been blocked or resulted in an empty response.";
        console.error("Full API response for debugging:", data); // Log the full response
      }
      throw new Error(errorMessage);
    }

    const aiReply = data.candidates[0].content.parts[0].text;
    messageElement.textContent = aiReply;

    conversation.push({
      role: "model",
      parts: [{ text: aiReply }],
    });
  } catch (error) {
    messageElement.classList.add("error");
    messageElement.textContent = "Oops! " + error.message;
    console.error("Error in generateResponse:", error);
  } finally {
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }
};

const handleChat = () => {
  userMessage = chatInput.value.trim();
  if (!userMessage) return;

  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;

  // Display user's message
  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  // Add user message to conversation history
  conversation.push({
    role: "user",
    parts: [{ text: userMessage }],
  });
  //
  // Generate and display AI's response
  setTimeout(() => {
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatbox.appendChild(incomingChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
    generateResponse(incomingChatLi);
  }, 600);
};

chatInput.addEventListener("input", () => {
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});

sendChatBtn.addEventListener("click", handleChat);

closeBtn.addEventListener("click", () => {
  document.body.classList.remove("show-chatbot");
  // Optional: Reset conversation and context when chatbot is closed
  // conversation = [];
  // pageContentCache = "";
  // chatbox.innerHTML = `<li class="chat incoming">... initial greeting ...</li>`; // Reset UI
});

chatbotToggler.addEventListener("click", () => {
  document.body.classList.toggle("show-chatbot");
  if (document.body.classList.contains("show-chatbot")) {
    // When chatbot opens, ensure context is set up if conversation is new or context not yet added.
    // `ensurePageContextInConversation` will be called by `generateResponse` anyway,
    // but calling it here can be an option if you want context ready even before the first message.
    // For current logic, it's fine as `generateResponse` handles it.
  } else {
    // Actions when chatbot is closed (same as closeBtn logic if needed)
  }
});

// Initial greeting is hardcoded in HTML.
// The page context will be added "silently" to the conversation history
// before the first request is sent to the API via `generateResponse`.
