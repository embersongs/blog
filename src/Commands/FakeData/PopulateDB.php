<?php
namespace Ember\Commands\FakeData;

use Ember\Blog\Comment;
use Ember\Blog\Post;
use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Comments\CommentsRepositoryInterface;
use Ember\Repositories\Posts\PostsRepositoryInterface;
use Ember\Repositories\Users\UsersRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private \Faker\Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addArgument('users-number', InputArgument::REQUIRED, 'Users number')
            ->addArgument('posts-number', InputArgument::REQUIRED, 'Posts number')
            ->addArgument('comments-number', InputArgument::REQUIRED, 'Comments number');
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $users_number = $input->getArgument('users-number');
        $posts_number = $input->getArgument('posts-number');
        $comments_number = $input->getArgument('comments-number');

        $users = [];

        for ($i = 0; $i < $users_number; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->username());
        }

        $posts = [];

        foreach ($users as $user) {
            for ($i = 0; $i < $posts_number; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getHeader());
            }
        }

        foreach ($posts as $post){
            for ($i = 0; $i < $comments_number; $i++) {
                $comment = $this->createFakeComment($post);
                $output->writeln('Comment created: ' . $comment->getText());
            }

        }

        return Command::SUCCESS;
    }

    private function createFakeComment(Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post->getAuthor(),
            $post,
            $this->faker->realText
        );
        $this->commentsRepository->save($comment);
        return $comment;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->password,
            new Name(
                $this->faker->firstName,
                $this->faker->lastName
            )
        );

        $this->usersRepository->save($user);
        return $user;
    }
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );
        $this->postsRepository->save($post);
        return $post;
    }
}
