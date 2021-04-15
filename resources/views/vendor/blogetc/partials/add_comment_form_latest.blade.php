<div class="comment__box">
               <span class="comment__count">
                 @if (count($comments) > 0)

                 @else
                   <img src="{{ asset('images/blog/comment-icon-grey.svg') }}" class="comment__img"> No comment yet
                 @endif

               </span>
               <div>
                   <p class="comment__sub-title">
                       Leave a Reply
                   </p>
                   <span class="comment__note">
                       Your Email Address will not be published
                   </span>

                   <div class="comment__container">
                       <form id="comment__form" method="post" action='{{route("blogetc.comments.add_new_comment", $post->slug)}}'>
                           @csrf

                           <textarea

                                   name='comment'
                                   required
                                   id="comment"
                                   placeholder="Your Comment"
                                   rows="7">{{old("comment")}}</textarea>
                           <div class="comment__check-box-group">
                             <!--
                               <div>
                                   <input type="checkbox" id="non-robot">
                                   <label for="non-robot" class="label">I am not a robot</label>
                               </div>
                               <div>
                                   <input type="checkbox" id="mailing-list">
                                   <label for="mailing-list" class="label">Add me to your mailing list</label>
                               </div>
                               -->
                           </div>

                           <div class="comment__other-info-group">
                               <div>

                                   <input
                                           type='text'
                                           class="form-control"
                                           name='author_name'
                                           id="author_name"
                                           placeholder="Your name"
                                           required
                                           value="{{old("author_name")}}">
                               </div>
                               <div>
                                 <input
                                         type='email'
                                         class="form-control"
                                         name='author_email'
                                         id="author_email"
                                         placeholder="Your Email"
                                         required
                                         value="{{old("author_email")}}">
                               </div>
                               <div>

                                   <input type='submit'
                                          value='Add Comment'>
                               </div>
                           </div>


                       </form>


                   </div>
               </div>
</div>
