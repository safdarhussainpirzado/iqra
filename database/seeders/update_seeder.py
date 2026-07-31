import json

json_path = 'database/seeders/computer_science_7.json'
with open(json_path, 'r') as f:
    data = json.load(f)

# 1. Update Unit 1: Add Q23 and Q24 to brief section if not already present
unit1 = data[0]
if "brief" in unit1:
    # Check if Q23 already there
    has_q23 = any("Graphics Processing Unit" in q["q"] for q in unit1["brief"])
    if not has_q23:
        unit1["brief"].append({
            "q": "What is the function of a Graphics Processing Unit (GPU) / Graphics Card?",
            "a": [
                "The function of a Graphics Processing Unit (GPU) / Graphics Card is rendering visual data that is outputted to a display device such as a monitor. It takes the load off the CPU and allows for high-quality visual display."
            ]
        })
        unit1["brief"].append({
            "q": "What are some examples of advanced graphical techniques used by a graphics card?",
            "a": [
                "Advanced graphical techniques used by a graphics card can include ray tracing, anti-aliasing, and texture filtering, which improve the quality of images and reduce visual artifacts."
            ]
        })
        # Update brief count in sections
        for sec in unit1["sections"]:
            if sec["key"] == "brief":
                sec["count"] = len(unit1["brief"])

# 2. Define full Unit 2 data structure
unit2 = {
  "num": 2,
  "title": "Digital Skills",
  "tag": "MS Word · PowerPoint · Email basics",
  "blurb": "Learn word processing, text formatting, multimedia presentations, and email protocols.",
  "color": "#8B5CF6",
  "available": True,
  "sections": [
    {"key": "tick", "label": "Tick the Answer", "count": 15},
    {"key": "brief", "label": "Brief Q&A", "count": 10},
    {"key": "detailed", "label": "Detailed Q&A", "count": 4},
    {"key": "mcq", "label": "Objective MCQs", "count": 15},
    {"key": "crq", "label": "Constructed Response Questions", "count": 10}
  ],
  "tick": [
    {
      "q": "To open a word document, we go to the ___ tab.",
      "options": ["Open", "File", "Home", "Insert"],
      "correct": 1
    },
    {
      "q": "___ is a word processor, that allows us to enter, format, save and print text.",
      "options": ["MS Excel", "MS PowerPoint", "MS Word", "MS Paint"],
      "correct": 2
    },
    {
      "q": "We can format the text from the ___ tab.",
      "options": ["File", "Format", "Insert", "Home"],
      "correct": 3
    },
    {
      "q": "___ make the text appear thicker and darker.",
      "options": ["Italics", "Format", "Font face", "Bold"],
      "correct": 3
    },
    {
      "q": "There are ___ types of alignments in text formatting in Word.",
      "options": ["3", "4", "5", "6"],
      "correct": 1
    },
    {
      "q": "The ___ tab lets you control the look and the feel of your document in Microsoft Word.",
      "options": ["Layout", "Margin", "Caption", "Format"],
      "correct": 0
    },
    {
      "q": "We can insert the image in the Word document from the ___ tab.",
      "options": ["Insert", "File", "Home", "View"],
      "correct": 0
    },
    {
      "q": "We can resize the image by clicking and dragging on its ___.",
      "options": ["Outline", "Center", "Resize handles", "Arrows"],
      "correct": 2
    },
    {
      "q": "The keyboard shortcut to copy is:",
      "options": ["Ctrl + X", "Ctrl + C", "Ctrl + P", "Ctrl + S"],
      "correct": 1
    },
    {
      "q": "We can insert the table in the Word document from the ___ tab.",
      "options": ["File", "Home", "Insert", "View"],
      "correct": 2
    },
    {
      "q": "___ appears at the top margin of the Word document.",
      "options": ["Footer", "Title", "Address bar", "Header"],
      "correct": 3
    },
    {
      "q": "To Print the Word document, we go to the ___ tab.",
      "options": ["Print", "File", "Format", "Insert"],
      "correct": 1
    },
    {
      "q": "The easiest way to create a multimedia presentation is to create it on:",
      "options": ["Microsoft Word", "Microsoft Excel", "Microsoft PowerPoint", "Microsoft Paint"],
      "correct": 2
    },
    {
      "q": "The keyboard shortcut to create new email is:",
      "options": ["Ctrl + S", "Ctrl + N", "Ctrl + M", "Ctrl + X"],
      "correct": 1
    },
    {
      "q": "It is always safe to ___ your account, if not using it.",
      "options": ["Protect", "Sign out", "Sign in", "Delete"],
      "correct": 1
    }
  ],
  "brief": [
    {
      "q": "Write a note on benefits of Microsoft PowerPoint which adds importance to any presentations for any kind of user.",
      "a": [
        "Some benefits of Microsoft PowerPoint which add importance to any of the presentations for any kind of user:",
        "<strong>(1) Point-to-Point Focus:</strong> PowerPoint helps in driving presentation-focused detail by pointing out important ideas or points through the slides. Rather than writing paragraphs or stories on what you would like to present, a detailed statement with some voice or Animated or Graphical representation will make it more eye-catching and interesting for listeners.",
        "<strong>(2) Attractive Visuals:</strong> Visual representation leaves an impression in our mind rather than listening to verbal statements. PowerPoint has different multimedia options that will help users to represent with a colourful background, text and animation giving striking look to the representation.",
        "<strong>(3) Numerous Resources:</strong> PowerPoint has numerous features to add references from the internet with a single click offering a series of cues.",
        "<strong>(4) Breaking the complexity:</strong> With the Presentation, the presenter gets the chance to use his skills to represent in a simpler way and saves the efforts of users and his own. PowerPoint also has the capability of using laser pointers for narration which helps the presenter to have eye-eye contact with the audience at the same time concentrating on the bullet points.",
        "<strong>(5) User Ownership:</strong> PowerPoint gives an option to digital signature to the presentation that will not let any of users the modify or add any of the content to the presentation without the consent of the user.",
        "<strong>(6) Multimedia capability:</strong> PowerPoint has the ability to convert the presentation into Video or audio and can be shared on the Internet and can be saved on DVD through a DVD burner for parties, functions, academics, entertainment, etc. It also has the capability to be viewed on any device like iPhone, Samsung Galaxy, Blueberry etc."
      ]
    },
    {
      "q": "State the different ways in which we can use email as a mean of authentication for another website.",
      "a": [
        "There are three common authentication types:",
        "<strong>(1) Password-based authentication:</strong> Passwords are the most common methods of authentication.",
        "<strong>(2) Certificate-based authentication:</strong> Uses digital certificates to identify users.",
        "<strong>(3) Biometric authentication:</strong> Uses unique biological characteristics like fingerprints or facial recognition to authenticate."
      ]
    },
    {
      "q": "Discuss the importance and uses of email.",
      "a": [
        "<strong>Importance of Email:</strong> An email is an essential tool for communication in today's modern world, and it has several advantages that make it a preferred choice for personal, professional, or academic communication. Key reasons include saving time/money, user-friendliness, simple documentation, and easy sharing of file attachments.",
        "<strong>Uses of Email:</strong>",
        "1. Communicate with people all over the world for free.",
        "2. Connect with more than one person at a time by sending group mails.",
        "3. Document interactions for future reference.",
        "4. Work in collaboration on mutual projects.",
        "5. Send attachments like images, files, or documents."
      ]
    },
    {
      "q": "Write the proper protocol of signing out the email account when not using it.",
      "a": [
        "When you are not using a personal device to access your email account, it is always safe to log out to prevent unauthorized access to your account. Follow the proper protocol outlined below:",
        "<strong>(1)</strong> Click on your user profile or username icon on the top right of the page, and a menu will be displayed.",
        "<strong>(2)</strong> From the menu options, click on the <strong>'Sign out'</strong> button.",
        "By following these steps, you will be securely signed out of your email account, and any unauthorized person will not be able to access your account without your login credentials."
      ]
    },
    {
      "q": "Elaborate on the use of thesaurus and synonyms features in Microsoft Word.",
      "a": [
        "The Thesaurus is a software tool that is used in Microsoft Word to look up (find) synonyms (words with the same meaning) and antonyms (words with the opposite meaning) for the selected word.",
        "<strong>Steps to use Thesaurus:</strong>",
        "1. Open a new or existing Word document.",
        "2. Highlight the typed or selected word you want to check.",
        "3. Right-click on the selected word to open the drop-down context menu.",
        "4. Place the cursor on the <strong>Synonyms</strong> option.",
        "5. Click on the word from the list that you think fits best."
      ]
    },
    {
      "q": "Differentiate between Save and Save As tool on the file tab.",
      "a": [
        "The difference between Save and Save As tool is summarized below:"
      ],
      "table": {
        "caption": "Comparison of Save vs Save As Commands",
        "head": ["SAVE", "SAVE AS"],
        "rows": [
          [
            "A command in the File menu that stores the data back to the file and folder it originally came from.",
            "A command in the File menu that allows to store a new file or storing the file in a new location."
          ],
          [
            "Helps to prevent data loss and to update the lastly preserved file with the latest content.",
            "Helps to store a new file or to store an existing file in a new location with the same name or with a different name."
          ],
          [
            "Applies to a current file.",
            "Can apply to a new file."
          ],
          [
            "Has only one step.",
            "Requires some additional steps."
          ],
          [
            "Does not allow saving the file in some other format.",
            "Allows the user to change the File format."
          ]
        ]
      }
    },
    {
      "q": "Describe the purpose of a word processor.",
      "a": [
        "<strong>Purpose of A Word Processor:</strong> The word processor is one of the most-used computer applications in education. There are four primary functions of word processors which are:",
        "1. <strong>Composing:</strong> Typing and generating new document text.",
        "2. <strong>Editing:</strong> Modifying existing text, spelling corrections, etc.",
        "3. <strong>Saving:</strong> Preserving the file onto secondary storage.",
        "4. <strong>Printing:</strong> Generating physical paper copies of the document."
      ]
    },
    {
      "q": "Identify and explain the common platforms for electronic mail.",
      "a": [
        "Electronic mail, commonly known as email, has several platforms or providers that offer email services:",
        "<strong>(1) Gmail:</strong> A free email service offered by Google. It is widely used due to its simple interface, high storage capacity, and integration with other Google services.",
        "<strong>(2) Microsoft Outlook:</strong> An email and personal information manager provided by Microsoft, offering calendar, contacts, and task management.",
        "<strong>(3) Yahoo Mail:</strong> A free email service provided by Yahoo, offering user-friendly themes, filters, and spam protection.",
        "<strong>(4) AOL Mail:</strong> A free service offering unlimited storage and virus protection.",
        "<strong>(5) Zoho Mail:</strong> A secure business email service that offers domain hosting and collaboration tools.",
        "<strong>(6) ProtonMail:</strong> A secure email service that offers end-to-end encryption to protect user privacy."
      ]
    },
    {
      "q": "Define Recipient, Attachments, Password, and Email address.",
      "a": [
        "<strong>Recipient:</strong> The person or group of people who receive an email message. The recipient's email address is entered in the 'To' field.",
        "<strong>Attachments:</strong> Files (documents, images, videos) that are included with an email message.",
        "<strong>Password:</strong> A secret code or phrase used to protect an email account from unauthorized access.",
        "<strong>Email address:</strong> A unique identifier used to send and receive email messages, consisting of a username and a domain name (e.g. username@domain.com)."
      ]
    },
    {
      "q": "Explain the standard protocols like SMTP and POP3.",
      "a": [
        "<strong>SMTP (Simple Mail Transfer Protocol):</strong> The principal email protocol responsible for the transfer of emails between email clients and email servers.",
        "<strong>POP3 (Post Office Protocol 3):</strong> A protocol used by email clients to download emails from the server to local storage. It is primarily a one-way download protocol."
      ]
    }
  ],
  "detailed": [
    {
      "q": "Prepare a presentation on a book, outlining introduction, analysis, conclusion, and tools used.",
      "a": [
        "<strong>Outline for Book Presentation:</strong>",
        "<strong>(1) Introduction:</strong> Introduce the book name, author, and summarize the plot briefly.",
        "<strong>(2) Analysis:</strong> Use SWOT analysis (Strengths, Weaknesses, Opportunities, Threats) to analyze the book content.",
        "<strong>(3) Conclusion:</strong> Summarize main points and encourage students to read more literature.",
        "<strong>(4) PowerPoint Tools used:</strong> Animations, slide transitions, slide layouts, text formatting, and inserted images."
      ]
    },
    {
      "q": "Create an email address using common freely available platforms like Microsoft or Google.",
      "a": [
        "<strong>Steps to Create a Gmail Address:</strong>",
        "1. Go to the Google sign-up page (accounts.google.com).",
        "2. Click on 'Create account' and fill in your first and last name.",
        "3. Choose a unique username before the @gmail.com domain.",
        "4. Create a strong password containing letters, numbers, and symbols.",
        "5. Enter recovery information (phone number or recovery email).",
        "6. Accept Google's Terms of Service to complete the process."
      ]
    },
    {
      "q": "Ask the students to write 3 things they have learned about email and 2 personal connections.",
      "a": [
        "<strong>Activity Guidelines:</strong>",
        "1. Write down 3 key concepts learned (e.g. SMTP protocol, safe sign-out habits, recipients).",
        "2. Name 2 personal connections (e.g. using email for school updates, emailing relatives).",
        "3. Name 1 area needing further clarification."
      ]
    },
    {
      "q": "Differentiate between PowerPoint slide design layouts and animations.",
      "a": [
        "Slide layout defines the organization of placeholders for titles, text, images, and charts.",
        "Animations apply motion effects to individual elements (such as text box fly-in, image fade) on a slide."
      ]
    }
  ],
  "mcq": [
    {
      "q": "What are the primary functions of a word processor?",
      "options": ["Composing, Editing, Saving, Printing", "Typing, Scanning, Drawing, Sorting", "Calculating, Charting, Presenting, Sharing", "Browsing, Emailing, Chatting, Searching"],
      "correct": 0
    },
    {
      "q": "Which software is used for word processing?",
      "options": ["Adobe Acrobat", "Microsoft Excel", "Microsoft Word", "Google Docs"],
      "correct": 2
    },
    {
      "q": "How can a new document be created in Microsoft Word?",
      "options": ["File > New", "File > Save", "Edit > New", "Format > New"],
      "correct": 0
    },
    {
      "q": "What is character formatting in Microsoft Word?",
      "options": ["Changing layout of document", "Changing font, size, and color of characters", "Changing margin sizes", "Inserting tables"],
      "correct": 1
    },
    {
      "q": "What are the three types of font styles on the home tab?",
      "options": ["Bold, Italics, Underline", "Normal, Heading, Title", "Arial, Times, Calibri", "Small, Medium, Large"],
      "correct": 0
    },
    {
      "q": "Which tab in Microsoft Word lets you control the look and feel?",
      "options": ["Home tab", "Insert tab", "Page Layout / Layout tab", "Review tab"],
      "correct": 2
    },
    {
      "q": "Which keyboard shortcut is used to paste text or an image?",
      "options": ["Ctrl + P", "Ctrl + V", "Ctrl + X", "Ctrl + C"],
      "correct": 1
    },
    {
      "q": "What is the purpose of a bulleted list?",
      "options": ["To highlight important items", "To list items in a certain order", "To display data in a table", "To add images"],
      "correct": 0
    },
    {
      "q": "Which options are not available in the Insert Picture menu?",
      "options": ["This Device", "Stock Images", "Online Pictures", "OneDrive"],
      "correct": 3
    },
    {
      "q": "How can you check for spelling and grammar errors?",
      "options": ["F7 key", "F5 key", "F1 key", "F12 key"],
      "correct": 0
    },
    {
      "q": "Which button on the formatting toolbar is used to create a bulleted list?",
      "options": ["Numbering button", "Bullets button", "Font size button", "Font color button"],
      "correct": 1
    },
    {
      "q": "What is the shortcut key for the 'Save' command?",
      "options": ["Ctrl+A", "Ctrl+S", "Ctrl+P", "Ctrl+Z"],
      "correct": 1
    },
    {
      "q": "Which command is used to apply changes to the current file?",
      "options": ["Save", "Save As", "Cut", "Paste"],
      "correct": 0
    },
    {
      "q": "Which command is used to create a new file and/or preserve the original?",
      "options": ["Save", "Save As", "Cut", "Paste"],
      "correct": 1
    },
    {
      "q": "Which tab contains the 'Table' button in Microsoft Word?",
      "options": ["Home", "Insert", "Layout", "References"],
      "correct": 1
    }
  ],
  "crq": [
    {
      "q": "What is a word processor?",
      "a": ["A word processor is a computer program or application software that allows users to enter, format, edit, save, and print text."]
    },
    {
      "q": "What is Microsoft Word?",
      "a": ["Microsoft Word is a popular word processing program used mainly for creating documents such as letters, brochures, learning activities, quizzes, tests, and student homework assignments."]
    },
    {
      "q": "How do you open a new document in Word?",
      "a": [
        "1. Open Word, or if Word is already open, select <strong>File > New</strong>.",
        "2. Click <strong>Blank Document</strong>, or select a template."
      ]
    },
    {
      "q": "What are some common features of Microsoft Word?",
      "a": ["Some of the most common features of Word include text formatting, editing, font styles (bold, italic, underline), resizing font size, alignment, margins, page layouts, and inserting tables/images."]
    },
    {
      "q": "What is character formatting in Word?",
      "a": ["Formatting that you apply to text is referred to as character formatting. In a Word document, you can apply character formatting such as font face, size, bold, italic, underline, subscript, superscript, color, and text highlights."]
    },
    {
      "q": "How can you apply character formatting to text?",
      "a": ["To format any text in a Word document, you need to select the text first, then apply formatting using the buttons on the Home tab ribbon."]
    },
    {
      "q": "What are the three types of font styles on the Home tab?",
      "a": ["The three basic font styles on the Home tab in Word are <strong>Bold, Italics, and Underline</strong>."]
    },
    {
      "q": "Why is it important to save a Word document?",
      "a": ["It is important to save a Word document after writing text in it so that it can be stored permanently and accessed later without losing any data."]
    },
    {
      "q": "How can you save a Word document?",
      "a": ["To save a Word document, click the <strong>File</strong> tab, select <strong>Save</strong>, choose a folder, type a filename, and click Save. Alternatively, press the keyboard shortcut <strong>Ctrl + S</strong>."]
    },
    {
      "q": "How can you open a saved file in Word?",
      "a": ["To open a saved file in Word, go to the <strong>File</strong> tab, click <strong>Open</strong>, navigate to the folder containing your file, select the file, and click Open."]
    }
  ]
}

data[1] = unit2

# 3. Define and populate Unit 3 structure
unit3 = {
  "num": 3,
  "title": "Computational Thinking",
  "tag": "Deconstruction · Generalization · Algorithms",
  "blurb": "Learn problem solving techniques, pattern recognition, and algorithm design.",
  "color": "#3B82F6",
  "available": True,
  "sections": [
    {"key": "tick", "label": "Tick the Answer", "count": 3}
  ],
  "tick": [
    {
      "q": "Breaking down a problem into sub-problems is called:",
      "options": ["generalization", "pattern Recognition", "deconstruction", "Design"],
      "correct": 2
    },
    {
      "q": "Discover the principles that cause the patterns of a problem is called:",
      "options": ["generalization", "pattern Recognition", "deconstruction", "Design"],
      "correct": 1
    },
    {
      "q": "Set of instructions to solve a problem is called:",
      "options": ["directions", "instructions", "algorithm", "Design"],
      "correct": 2
    }
  ]
}

data[2] = unit3

with open(json_path, 'w') as f:
    json.dump(data, f, indent=2)

print("Unit 1 additions, Unit 2 transcription, and Unit 3 ticks successfully updated in seeder JSON!")
