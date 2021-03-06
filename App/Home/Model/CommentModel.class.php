<?php
/**
 * bg_comment操作模型
 */
class CommentModel extends Model{
	/**
	 * 添加评论
	 */
	public function insertComment($cmtInfo){
		extract($cmtInfo);
		$sql="insert into bg_comment values (null,$art_id,'$cmt_user','$cmt_content','$cmt_time')";
		return $this->dao->my_query($sql);
	}
	/**
	 * 根据文章id号获取当前总评论数
	 */
	public function getRowCountByArtId($art_id){
		$sql="select count(*) from bg_comment where art_id=$art_id";
		return $this->dao->fetchColumn($sql);
	}
	/**
	 * 根据文章id号获取当前页评论数
	 */
	public function getCmtInfosById($art_id,$rowsPerPage){
		$pageNum=isset($_GET['pageNum'])? $_GET['pageNum']:1;
		$offset=($pageNum-1)*$rowsPerPage;
		$sql="select c.*,u.user_image from bg_comment as c left join bg_user as u on c.cmt_user=u.user_name where c.art_id=$art_id order by c.cmt_time limit $offset,$rowsPerPage";
		return $this->dao->fetchAll($sql);
;	}
}