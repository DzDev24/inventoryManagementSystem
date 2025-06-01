           <?php if (isset($_SESSION['user_role'])): ?>

               <div class=chatbot-main>
                   <button class="chatbot-toggler">
                       <span class="material-symbols-rounded">mode_comment</span>
                       <span class="material-symbols-outlined">close</span>
                   </button>
                   <div class="chatbot">
                       <header>
                           <h2 class="text-light">Chatbot</h2>
                           <span class="close-btn material-symbols-outlined">close</span>
                       </header>
                       <ul class="chatbox">
                           <li class="chat incoming">
                               <span class="material-symbols-outlined">smart_toy</span>
                               <p>Hi there 👋<br />I am chatbot of IMS-25. How can I help you today?</p>

                           </li>
                            <li class="chat outgoing"><p>afoja fafkaofaof</p></li>
                            <li class="chat incoming"><span></span><p>Thinking</p></li>



                       </ul>
                       <div class="chat-input">
                           <textarea placeholder="Enter a message..." spellcheck="false" required></textarea>
                           <span id="send-btn" class="material-symbols-rounded">send</span>
                       </div>
                   </div>
               </div>


           <?php endif; ?>


           <footer class="footer-admin mt-auto footer-light">
               <div class="container-xl px-4">
                   <div class="row">
                       <div class="col-md-6 small">Copyright &copy; Inventory Management System <?= date('Y'); ?></div>
                       <div class="col-md-6 text-md-end small">
                           <a href="Privacy_Policy_TOS/privacy_policy_inventory_management_system.pdf">Privacy Policy</a>
                           &middot;
                           <a href="Privacy_Policy_TOS/terms_and_conditions_inventory_management_system.pdf">Terms &amp; Conditions</a>
                       </div>
                   </div>
               </div>
           </footer>