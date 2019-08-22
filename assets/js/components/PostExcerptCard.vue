<template>
  <a class="card post-excerpt-card tooltip is-tooltip-multiline"
     :href="postHref" :data-tooltip="'« ' + postExcerpt + ' [...]'"
  >
    <div class="card-content">
      <figure class="columns image is-hidden-mobile">
        <img class="column is-rounded" :src="authorImg" :alt="authorName">
        <figcaption class="column">
          <a :href="authorHref">{{ authorName }}</a>
        </figcaption>
      </figure>
      <div>
        <p class="content is-small">
          Dernier post le {{ postDate }} <br/>
          <em><a href="#">{{ threadTitle }}</a></em>
        </p>
      </div>
    </div>
  </a>
</template>

<script>
  export default {
    props: {
      authorName: String,
      authorImg: String,
      authorHref: String,
      postExcerpt: String,
      postHref: String,
      postDate: String,
      threadTitle: String,
      threadHref: String
    }
  }
</script>

<style lang="scss">
  .post-excerpt-card {
    img {
      max-width: 80px;
      max-height: 80px;
    }
  }
</style>
