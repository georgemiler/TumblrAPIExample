Tumblr API Example
==================

A simple website that does the following.

* Build a form that accepts a Tumblr blog name as its input and use the Tumblr API to retrieve posts for that blog.
* Results should be shown 10 at a time and have correctly functioning pagination links. ie. 'next' link only works if there are more posts, '3' link should show results 21-30, etc.
* For each post, show its post ID, publish date and a link to the post on Tumblr.
* Use of a PHP framework is not required but strongly encouraged. You may choose the framework you are most comfortable with.
* An emphasis should not be given on design. However, the post results should still be readable.

### Implementation

We're using [CakePHP](http://cakephp.org/) and [Bootstrap 3.0](http://getbootstrap.com/)

### Limitations

* Functional but not pretty in Internet Explorer <= 8
* A little slow when loading blogs that have large numbers of posts
