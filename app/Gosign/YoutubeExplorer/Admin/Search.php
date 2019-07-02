<?php

namespace Gosign\YoutubeExplorer\Admin;

use Gosign\YoutubeExplorer\Pattern\Singleton;

/**
 * Search form
 *
 * @package Youtube_Explorer
 */
class Search extends Singleton {

	/**
	 * Render search form.
	 */
	public function display() {
		$videos = [];
		if ( isset( $_POST['youtube-explorer-nonce'] ) && $_POST['youtube-explorer-nonce'] ) {
			if ( check_admin_referer( 'youtube-explorer', 'youtube-explorer-nonce' ) ) {
				$videos = $this->search();
			}
		}
		$q          = isset( $_POST['q'] ) ? $_POST['q'] : '';
		$maxResults = isset( $_POST['maxResults'] ) ? $_POST['maxResults'] : 5;
		$order      = isset( $_POST['order'] ) ? $_POST['order'] : 'relevance';
		$type       = isset( $_POST['type'] ) ? $_POST['type'] : [ 'video' ];
		?>
        <h2>Search Youtube</h2>

        <form method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
			<?php wp_nonce_field( 'youtube-explorer', 'youtube-explorer-nonce' ); ?>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label
                                for="q"><?php esc_html_e( 'Keyword', 'youtube-explorer' ); ?></label>
                    </th>
                    <td><input name="q" type="text" id="q" required
                               placeholder="<?php esc_html_e( 'Input search keyword', 'youtube-explorer' ); ?>"
                               value="<?php echo esc_attr( $q ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="maxResults"><?php esc_html_e( 'Max results', 'youtube-explorer' ); ?></label>
                    </th>
                    <td><input name="maxResults" type="number" id="maxResults"
                               step="1" min="1" max="50"
                               value="<?php echo esc_attr( $maxResults ); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="order"><?php esc_html_e( 'Order', 'youtube-explorer' ); ?></label>
                    </th>
                    <td>
                        <select name="order">
                            <option value="date"
							        <?php if ( 'date' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'Date', 'youtube-explorer' ); ?></option>
                            <option value="rating"
							        <?php if ( 'rating' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'Rating', 'youtube-explorer' ); ?></option>
                            <option value="relevance"
							        <?php if ( 'relevance' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'Relevance', 'youtube-explorer' ); ?></option>
                            <option value="title"
							        <?php if ( 'title' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'Title', 'youtube-explorer' ); ?></option>
                            <option value="videoCount"
							        <?php if ( 'videoCount' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'Video Count', 'youtube-explorer' ); ?></option>
                            <option value="viewCount"
							        <?php if ( 'viewCount' === $order ): ?>selected<?php endif; ?>><?php esc_html_e( 'View Count', 'youtube-explorer' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="type"><?php esc_html_e( 'Type', 'youtube-explorer' ); ?></label>
                    </th>
                    <td>
                        <label for="type_video">
                            <input name="type[]" type="checkbox" id="type_video" value="video"
							       <?php if ( in_array( 'video', $type ) ): ?>checked="checked"<?php endif; ?>>
							<?php esc_html_e( 'Video', 'youtube-explorer' ); ?>
                        </label>
                        <label for="type_channel">
                            <input name="type[]" type="checkbox" id="type_channel" value="channel"
							       <?php if ( in_array( 'channel', $type ) ): ?>checked="checked"<?php endif; ?>>
							<?php esc_html_e( 'Channel', 'youtube-explorer' ); ?>
                        </label>
                        <label for="type_playlist">
                            <input name="type[]" type="checkbox" id="type_playlist" value="playlist"
							       <?php if ( in_array( 'playlist', $type ) ): ?>checked="checked"<?php endif; ?>>
							<?php esc_html_e( 'Playlist', 'youtube-explorer' ); ?>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary"
                       value="<?php esc_html_e( 'Search', 'youtube-explorer' ); ?>">
            </p>
        </form>
		<?php
		if ( $videos ):
			?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th><?php esc_html_e( 'Thumbnail', 'youtube-explorer' ); ?></th>
                    <th><?php esc_html_e( 'Title', 'youtube-explorer' ); ?></th>
                    <th><?php esc_html_e( 'Id', 'youtube-explorer' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'youtube-explorer' ); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ( $videos as $video ):
					?>
                    <tr>
                        <td>
                            <iframe width="240" height="135"
                                    src="https://www.youtube.com/embed/<?php echo $video['id']; ?>" frameborder="0"
                                    allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </td>
                        <td><?php echo esc_html( $video['title'] ); ?></td>
                        <td><?php echo esc_html( $video['id'] ); ?></td>
                        <td><?php $this->the_action( $video ); ?></td>
                    </tr>
				<?php
				endforeach;
				?>
                </tbody>
            </table>
		<?php
		endif;
	}

	/**
	 * Output action source.
	 *
	 * @param $video
	 */
	public function the_action( $video ) {
		$action = sprintf( '<a href="https://www.youtube.com/watch?v=%s" target="_blank">%s</a>',
			$video['id'],
			__( 'View movie', 'youtube-explorer' ) );

		/**
		 * Filters the action source.
		 *
		 * @param string $action
		 * @param array $video
		 */
		$action = apply_filters( 'youtube_explorer_the_action', $action, $video );

		echo $action;
	}

	/**
	 * Search Youtube API.
	 */
	public function search() {
		$google_api_key = $this->options['google_api_key'];

		$client = new \Google_Client();
		$client->setDeveloperKey( $google_api_key );

		$youtube = new \Google_Service_YouTube( $client );

		try {
			$searchResponse = $youtube->search->listSearch( 'id,snippet', [
				'q'               => $_POST['q'],
				'maxResults'      => $_POST['maxResults'],
				'order'           => $_POST['order'],
				'type'            => implode( ',', $_POST['type'] ),
				'videoEmbeddable' => 'true',
			] );

			$videos = [];

			foreach ( $searchResponse['items'] as $searchResult ) {
				switch ( $searchResult['id']['kind'] ) {
					case 'youtube#video':
						$videos[] = [
							'id'        => $searchResult['id']['videoId'],
							'type'      => 'video',
							'title'     => $searchResult['snippet']['title'],
							'thumbnail' => $searchResult['snippet']['thumbnails']->default->url,
						];
						break;
					case 'youtube#channel':
						$videos[] = [
							'id'        => $searchResult['id']['channelId'],
							'type'      => 'channel',
							'title'     => $searchResult['snippet']['title'],
							'thumbnail' => $searchResult['snippet']['thumbnails']->default->url,
						];
						break;
					case 'youtube#playlist':
						$videos[] = [
							'id'        => $searchResult['id']['playlistId'],
							'type'      => 'playlist',
							'title'     => $searchResult['snippet']['title'],
							'thumbnail' => $searchResult['snippet']['thumbnails']->default->url,
						];
						break;
				}
			}
		} catch ( Google_Service_Exception $e ) {
			throw new \Exception( sprintf( '<p>A service error occurred: <code>%s</code></p>', htmlspecialchars( $e->getMessage() ) ) );
		} catch ( Google_Exception $e ) {
			throw new \Exception( sprintf( '<p>A service error occurred: <code>%s</code></p>', htmlspecialchars( $e->getMessage() ) ) );
		}

		return $videos;
	}
